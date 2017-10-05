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

class ConnectorModel
{

    CONST FILE_LIST = 'fileObjects';

    /**
     * Importuje dane
     * @param string $importData
     * @return boolean
     */
    public function importData($importData)
    {
        if (!is_array($decodedData = json_decode($importData, true))) {
            return false;
        }
        \App\Registry::$db->query('SET FOREIGN_KEY_CHECKS = 0;');
        \App\Registry::$db->setDefaultImportParams()
            ->beginTransaction();
        foreach ($decodedData as $tableName => $data) {
            //puste dane
            if (empty($data)) {
                continue;
            }
            //czyszczenie tabeli
            \App\Registry::$db->delete($tableName);
            //wstawienie danych
            \App\Registry::$db->insertAll($tableName, $data);
        }
        return \App\Registry::$db->commit();
    }

    public function getExportData($acl = true, $content = true)
    {
        if ($acl) {
            $exportData['cms_role'] = \App\Registry::$db->select('*', 'cms_role');
            $exportData['cms_acl'] = \App\Registry::$db->select('*', 'cms_acl');
        }
        if ($content) {
            $exportData['cms_attribute_type'] = \App\Registry::$db->select('*', 'cms_attribute_type');
            $exportData['cms_attribute'] = \App\Registry::$db->select('*', 'cms_attribute');
            $exportData['cms_attribute_relation'] = \App\Registry::$db->select('*', 'cms_attribute_relation');
            $exportData['cms_attribute_value'] = \App\Registry::$db->select('*', 'cms_attribute_value');
            $exportData['cms_attribute_value_relation'] = \App\Registry::$db->select('*', 'cms_attribute_value_relation');
            $exportData['cms_category_type'] = \App\Registry::$db->select('*', 'cms_category_type');
            $exportData['cms_category'] = \App\Registry::$db->select('*', 'cms_category');
            $exportData['cms_category_acl'] = \App\Registry::$db->select('*', 'cms_category_acl');
            $exportData['cms_category_relation'] = \App\Registry::$db->select('*', 'cms_category_relation');
            $exportData['cms_category_widget'] = \App\Registry::$db->select('*', 'cms_category_widget');
            $exportData['cms_category_widget_category'] = \App\Registry::$db->select('*', 'cms_category_widget_category');
            $exportData['cms_mail_server'] = \App\Registry::$db->select('*', 'cms_mail_server');
            $exportData['cms_mail_definition'] = \App\Registry::$db->select('*', 'cms_mail_definition');
            $exportData['cms_text'] = \App\Registry::$db->select('*', 'cms_text');
        }
        return $exportData;
    }

    public function getFileObjects()
    {
        $fileObjects = [];
        foreach ((new Orm\CmsFileQuery)->findUnique('object') as $object) {
            if ('tmp-' == substr($object, 0, 4)) {
                continue;
            }
            $fileObjects[$object] = $object;
        }
        return $fileObjects;
    }
    
    public function getFileMeta($objects) {
        if (!is_array($objects)) {
            return [];
        }
        return (new Orm\CmsFileQuery)->whereObject()->equals($objects)
            ->find()
            ->toArray();
    }

    public function getInstanceHash()
    {
        //zwraca md5 ze zrzutu MmiChangelog wraz z saltem
        return md5(print_r((new \Mmi\Orm\ChangelogQuery)
                    ->orderAscFilename()
                    ->findPairs('filename', 'md5'), true) . \App\Registry::$config->salt);
    }

}
