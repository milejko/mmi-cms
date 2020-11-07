<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Cms\Orm;
use Mmi\App\App;
use Mmi\Mvc\View;
use PHPMailer\PHPMailer\PHPMailer;
use Psr\Log\LoggerInterface;

class Mail
{

    /**
     * Czyści wysłane starsze niż tydzień
     * @return integer ilość usuniętych
     */
    public static function clean()
    {
        return (new Orm\CmsMailQuery())
            ->whereActive()->equals(1)
            ->andFieldDateSent()->less(date('Y-m-d H:i:s', strtotime('-1 week')))
            ->delete();
    }

    /**
     * Dodaje email do kolejki
     * @param string $name nazwa-klucz e-maila z definicji
     * @param string $to adres do lub adresy oddzielone ";"
     * @param array $params zmienne do podstawienia w treści maila
     * @param string $fromName nazwa od
     * @param string $replyTo adres odpowiedz do
     * @param string $subject temat
     * @param string $sendAfter data i czas wyślij po
     * @param array $attachments tabela z załącznikami w postaci ['nazwa dla usera' => 'fizyczna ścieżka i nazwa pliku']
     * @return int id zapisanego rekordu
     */
    public static function pushEmail($name, $to, array $params = [], $fromName = null, $replyTo = null, $subject = null, $sendAfter = null, array $attachments = [])
    {
        //brak definicji
        if (null === ($def = Orm\CmsMailDefinitionQuery::langByName($name)
            ->findFirst())) {
            return false;
        }
        //walidacja listy adresów "do"
        $email = new \Mmi\Validator\EmailAddressList;
        if (!$email->isValid($to)) {
            return false;
        }
        //nowy rekord maila
        $mailRecord = new Orm\CmsMailRecord;
        $mailRecord->cmsMailDefinitionId = $def->id;
        $mailRecord->to = $to;
        $mailRecord->fromName = $fromName ? $fromName : $def->fromName;
        $mailRecord->replyTo = $replyTo ? $replyTo : $def->replyTo;
        $mailRecord->subject = $subject ? $subject : $def->subject;
        $mailRecord->dateSendAfter = $sendAfter ? $sendAfter : date('Y-m-d H:i:s');
        $files = [];
        //załączniki
        foreach ($attachments as $fileName => $filePath) {
            if (!file_exists($filePath)) {
                continue;
            }
            $files[$fileName] = ($filePath);
        }
        //serializacja załączników
        $mailRecord->attachements = serialize($files);
        //przepychanie zmiennych do widoku
        $view = App::$di->get(View::class);
        foreach ($params as $key => $value) {
            $view->$key = $value;
        }
        //rendering wiadomości
        $mailRecord->message = $view->renderDirectly($def->message);
        //rendering tematu
        $mailRecord->subject = $view->renderDirectly($mailRecord->subject);
        $mailRecord->dateAdd = date('Y-m-d H:i:s');
        //zapis maila
        return $mailRecord->save();
    }

    /**
     * Wysyła maile z kolejki
     * @return int ilość wysłanych
     */
    public static function send()
    {
        //rezultat wysyłania
        $result = ['error' => 0, 'success' => 0];
        //pobieranie maili
        $emails = (new Orm\CmsMailQuery)
            ->join('cms_mail_definition')->on('cms_mail_definition_id')
            ->join('cms_mail_server', 'cms_mail_definition')->on('cms_mail_server_id')
            ->whereActive()->equals(0)
            ->andFieldDateSendAfter()->lessOrEquals(date('Y-m-d H:i:s'))
            ->orderAscDateSendAfter()
            ->limit(50)
            ->find();
        //brak maili do wysyłki
        if (count($emails) == 0) {
            return $result;
        }
        //tymczasowy stan (w wysyłce)
        foreach ($emails as $email) {
            $email->active = 2;
            $email->save();
        }
        //wysyłka pojedynczego maila
        foreach ($emails as $email) {
            //instancja PHPMailer'a
            $mailer = new PHPMailer(true);
            try {
                $mailer->CharSet = 'utf-8';
                //ustawiam SMTP
                $mailer->isSMTP();
                //ustawianie hosta
                $mailer->Host = $email->getJoined('cms_mail_server')->address;
                //ustawianie portu
                $mailer->Port = $email->getJoined('cms_mail_server')->port;
                //jezeli podana nazwa uzytkownika i haslo to wlaczamy uwierzytelnienie
                if ($email->getJoined('cms_mail_server')->username && $email->getJoined('cms_mail_server')->password) {
                    //uwierzytelnianie serwera
                    $mailer->SMTPAuth = true;
                    //typ uwierzytelniania
                    $mailer->AuthType = 'LOGIN';
                    //uzytkownik SMTP
                    $mailer->Username = $email->getJoined('cms_mail_server')->username;
                    //haslo SMTP
                    $mailer->Password = $email->getJoined('cms_mail_server')->password;
                }
                if ($email->getJoined('cms_mail_server')->ssl != 'plain') {
                    //szyfrowanie polaczenia
                    $mailer->SMTPSecure = $email->getJoined('cms_mail_server')->ssl;
                }
                //ustawianie treści wiadomości
                $mailer->Body = strip_tags($email->message);
                //jezeli HTML to ustawiam typ wiadomości na HTML
                if ($email->getJoined('cms_mail_definition')->html) {
                    $mailer->isHTML(true);
                    $mailer->Body = $email->message;
                }
                //ustawianie "from"
                $mailer->setFrom($email->getJoined('cms_mail_server')->from, $email->fromName);
                //ustawianie "do"
                $recipients = explode(';', $email->to);
                foreach ($recipients as $recipient) {
                    $mailer->addAddress(trim($recipient));
                }
                //ustawianie reply
                if ($email->replyTo) {
                    $mailer->addReplyTo($email->replyTo);
                }
                //ustawianie tematu
                $mailer->Subject = $email->subject;
                //dołączanie załączników
                $attachments = unserialize($email->attachements);
                if (!empty($attachments)) {
                    foreach ($attachments as $fileName => $filePath) {
                        if (!file_exists($filePath)) {
                            continue;
                        }
                        $file = [
                            'content' => base64_encode(file_get_contents($filePath)),
                            'type' => \Mmi\FileSystem::mimeType($filePath)
                        ];
                        $mailer->addStringAttachment(base64_decode($file['content']), $fileName, 'base64', $file['type'], 'attachment');
                    }
                }
                //wysyłka maila
                $mailer->send();
                //czyszczenie załączników
                $email->attachements = null;
                //ustawienie pol po wysłaniu
                $email->active = 1;
                $email->dateSent = date('Y-m-d H:i:s');
                $email->save();
                //podwyzszenie licznika udanych
                $result['success'] ++;
            } catch (\Exception $e) {
                //bład wysyłki
                App::$di->get(LoggerInterface::class)->warning($e->getMessage());
                //podwyzszenie licznika nieudanych
                $result['error'] ++;
            }
        }
        return $result;
    }

    /**
     * Pobiera aktywne serwery do listy
     * @return array lista
     */
    public static function getMultioptions()
    {
        $rows = (new Orm\CmsMailServerQuery)
            ->whereActive()->equals(1)
            ->find();
        $pairs = [];
        foreach ($rows as $row) {
            $pairs[$row->id] = $row->address . ':' . $row->port . ' (' . $row->username . ')';
        }
        return $pairs;
    }

}
