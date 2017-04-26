<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use \Cms\Orm;

class Mail
{

    /**
     * Czyści wysłane starsze niż tydzień
     * @return integer ilość usuniętych
     */
    public static function clean()
    {
        return (new Orm\CmsMailQuery)
                ->whereActive()->equals(1)
                ->andFieldDateAdd()->less(date('Y-m-d H:i:s', strtotime('-1 week')))
                ->find()
                //kasowanie całej kolekcji
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
        $mail = new Orm\CmsMailRecord;
        $mail->cmsMailDefinitionId = $def->id;
        $mail->to = $to;
        $mail->fromName = $fromName ? $fromName : $def->fromName;
        $mail->replyTo = $replyTo ? $replyTo : $def->replyTo;
        $mail->subject = $subject ? $subject : $def->subject;
        $mail->dateSendAfter = $sendAfter ? $sendAfter : date('Y-m-d H:i:s');
        $files = [];
        //załączniki
        foreach ($attachments as $fileName => $filePath) {
            if (!file_exists($filePath)) {
                continue;
            }
            $files[$fileName] = [
                'content' => base64_encode(file_get_contents($filePath)),
                'type' => \Mmi\FileSystem::mimeType($filePath)
            ];
        }
        //serializacja załączników
        $mail->attachements = serialize($files);
        //przepychanie zmiennych do widoku
        $view = \Mmi\App\FrontController::getInstance()->getView();
        foreach ($params as $key => $value) {
            $view->$key = $value;
        }
        //rendering wiadomości
        $mail->message = $view->renderDirectly($def->message);
        //rendering tematu
        $mail->subject = $view->renderDirectly($mail->subject);
        $mail->dateAdd = date('Y-m-d H:i:s');
        //zapis maila
        return $mail->save();
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
            ->limit(500)
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
            $mail = new \PHPMailer(true);
            try {
                $mail->CharSet = 'utf-8';
                //ustawiam SMTP
                $mail->isSMTP();
                //ustawianie hosta
                $mail->Host = $email->getJoined('cms_mail_server')->address;
                //ustawianie portu
                $mail->Port = $email->getJoined('cms_mail_server')->port;
                //jezeli podana nazwa uzytkownika i haslo to wlaczamy uwierzytelnienie
                if ($email->getJoined('cms_mail_server')->username && $email->getJoined('cms_mail_server')->password) {
                    //uwierzytelnianie serwera
                    $mail->SMTPAuth = true;
                    //typ uwierzytelniania
                    $mail->AuthType = 'LOGIN';
                    //uzytkownik SMTP
                    $mail->Username = $email->getJoined('cms_mail_server')->username;
                    //haslo SMTP
                    $mail->Password = $email->getJoined('cms_mail_server')->password;
                }
                if ($email->getJoined('cms_mail_server')->ssl != 'plain') {
                    //szyfrowanie polaczenia
                    $mail->SMTPSecure = $email->getJoined('cms_mail_server')->ssl;
                }
                //ustawianie treści wiadomości
                $mail->Body = strip_tags($email->message);
                //jezeli HTML to ustawiam typ wiadomości na HTML
                if ($email->getJoined('cms_mail_definition')->html) {
                    $mail->isHTML(true);
                    $mail->Body = $email->message;
                }
                //ustawianie "from"
                $mail->setFrom($email->getJoined('cms_mail_server')->from, $email->fromName);
                //ustawianie "do"
                $recipients = explode(';', $email->to);
                foreach ($recipients as $recipient) {
                    $mail->addAddress(trim($recipient));
                }
                //ustawianie reply
                if ($email->replyTo) {
                    $mail->addReplyTo($email->replyTo);
                }
                //ustawianie tematu
                $mail->Subject = $email->subject;
                //dołączanie załączników
                $attachments = unserialize($email->attachements);
                if (!empty($attachments)) {
                    foreach ($attachments as $fileName => $file) {
                        if (!isset($file['content']) || !isset($file['type'])) {
                            continue;
                        }
                        $mail->addStringAttachment(base64_decode($file['content']), $fileName, 'base64', $file['type'], 'attachment');
                    }
                }
                //wysyłka maila
                $mail->send();
                //ustawienie pol po wysłaniu
                $email->active = 1;
                $email->dateSent = date('Y-m-d H:i:s');
                $email->save();
                //podwyzszenie licznika udanych
                $result['success'] ++;
            } catch (\Exception $e) {
                //bład wysyłki
                \Mmi\App\FrontController::getInstance()->getLogger()->addWarning($e->getMessage());
                //jak nie da sie wysłac to usuwamy
                $email->delete();
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
