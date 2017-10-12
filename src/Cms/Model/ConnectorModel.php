<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

/**
 * Model połączeń między CMSami
 */
class ConnectorModel
{

    //przestrzeń w sesji przechowująca dane importu
    CONST SESSION_SPACE = 'connector-data';

    /**
     * Importuje dane
     * @param array $importData
     * @return boolean
     */
    public function importData(array $importData)
    {
        //@TODO przeniesienie do setDefaultImportParams
        \App\Registry::$db->query('SET FOREIGN_KEY_CHECKS = 0;');
        \App\Registry::$db->setDefaultImportParams()
            ->beginTransaction();
        //iteracja po danych z tabel
        foreach ($importData as $tableName => $data) {
            \App\Registry::$db;
            //puste dane
            if (empty($data)) {
                continue;
            }
            //czyszczenie tabeli
            \App\Registry::$db->delete($tableName);
            //wstawienie danych
            \App\Registry::$db->insertAll($tableName, $data);
        }
        //commit transakcji
        return \App\Registry::$db->commit();
    }

    /**
     * Importuje meta pliku
     * @param array $importData
     * @return \Cms\Orm\CmsFileRecord
     */
    public function importFileMeta(array $importData)
    {
        //brak id
        if (!isset($importData['name']) || !isset($importData['id'])) {
            return;
        }
        //sprawdzanie istnienia pliku
        if (null === $file = (new \Cms\Orm\CmsFileQuery)->whereName()->equals($importData['name'])->findFirst()) {
            $file = new \Cms\Orm\CmsFileRecord;
        }
        //identyfikatory niezgodne
        if ($file->id && $file->id != $importData['id']) {
            return;
        }
        //ustawienie danych rekordu rekordu
        $file->setFromArray($importData);
        //poprawny zapis
        if ($file->save()) {
            //zwrot rekordu
            return $file;
        }
    }

    /**
     * Pobranie danych
     * @param bool $acl
     * @param bool $content
     * @return array
     */
    public function getExportData($acl = true, $content = true)
    {
        //inicjalizacja
        $exportData = [];
        //część aclowa
        if ($acl) {
            $exportData['cms_role'] = \App\Registry::$db->select('*', 'cms_role');
            $exportData['cms_acl'] = \App\Registry::$db->select('*', 'cms_acl');
        }
        //content
        if ($content) {
            //atrybuty
            $exportData['cms_attribute_type'] = \App\Registry::$db->select('*', 'cms_attribute_type');
            $exportData['cms_attribute'] = \App\Registry::$db->select('*', 'cms_attribute');
            $exportData['cms_attribute_relation'] = \App\Registry::$db->select('*', 'cms_attribute_relation');
            $exportData['cms_attribute_value'] = \App\Registry::$db->select('*', 'cms_attribute_value');
            $exportData['cms_attribute_value_relation'] = \App\Registry::$db->select('*', 'cms_attribute_value_relation');
            //kateogrie
            $exportData['cms_category_type'] = \App\Registry::$db->select('*', 'cms_category_type');
            $exportData['cms_category'] = \App\Registry::$db->select('*', 'cms_category');
            $exportData['cms_category_acl'] = \App\Registry::$db->select('*', 'cms_category_acl');
            $exportData['cms_category_relation'] = \App\Registry::$db->select('*', 'cms_category_relation');
            $exportData['cms_category_widget'] = \App\Registry::$db->select('*', 'cms_category_widget');
            $exportData['cms_category_widget_category'] = \App\Registry::$db->select('*', 'cms_category_widget_category');
            //mail-serwer
            $exportData['cms_mail_server'] = \App\Registry::$db->select('*', 'cms_mail_server');
            $exportData['cms_mail_definition'] = \App\Registry::$db->select('*', 'cms_mail_definition');
            //teksty stałe
            $exportData['cms_text'] = \App\Registry::$db->select('*', 'cms_text');
        }
        //zwrot danych
        return $exportData;
    }

    /**
     * Pobiera typy obiektów
     * @return array
     */
    public function getFileObjects()
    {
        $fileObjects = [];
        //iteracja po unikalnych obiektach
        foreach ((new \Cms\Orm\CmsFileQuery)->findUnique('object') as $object) {
            //pominięcie plików tymczasowych
            if ('tmp-' == substr($object, 0, 4)) {
                continue;
            }
            //dodanie pliku
            $fileObjects[$object] = $object;
        }
        return $fileObjects;
    }

    /**
     * Pobranie plików dla podanych obiektów (np. news, categorywidget itp.)
     * @param array $objects
     * @return array indeksowane po nazwie md5
     */
    public function getFileList($objects)
    {
        //błąd
        if (!is_array($objects)) {
            return [];
        }
        //zwrot meta plików
        return (new \Cms\Orm\CmsFileQuery)->whereObject()->equals($objects)
                ->findPairs('name', 'original');
    }

    /**
     * Pobranie hasha instancji CMS (wersja + salt)
     * @return string
     */
    public function getInstanceHash()
    {
        //zwraca md5 ze zrzutu MmiChangelog (część cms) wraz z saltem
        return md5(print_r((new \Mmi\Orm\ChangelogQuery)
                    ->whereFilename()->like('%_cms_%')
                    ->orderAscFilename()
                    ->findPairs('filename', 'md5'), true) . \App\Registry::$config->salt);
    }

    /**
     * 
     * @param string $url
     * @param array $params
     * @param string $identity
     * @param string $credential
     * @return array
     * @throws \Cms\Exception\ConnectorException
     */
    public function getData($url, $action, array $params = [], $identity = '', $credential = '')
    {
        try {
            //zwrot zdekodowanego json'a
            $response = json_decode(file_get_contents($url . '/?' . http_build_query(['module' => 'cms',
                        'controller' => 'connector',
                        'action' => $action
                    ]), false, stream_context_create(['http' => [
                    //post
                    'method' => 'POST',
                    //nagłówek formularza
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    //parametry
                    'content' => http_build_query($params + [
                        'identity' => $identity,
                        'credential' => $credential,
                        'instanceHash' => $this->getInstanceHash()
                    ])
                ]]
                )), true);
        } catch (\Exception $e) {
            //wyjątki
            throw new \Cms\Exception\ConnectorException($e->getMessage());
        }
        //w odpowiedzi jest status a nie dane
        if (isset($response['status'])) {
            throw new \Cms\Exception\ConnectorException('Status returned instead of data');
        }
        return $response;
    }

}
