<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form\Text;

/**
 * Formularz kopiowania tekstów statycznych
 */
class Copy extends \Mmi\Form\Form
{

    public function init()
    {

        $langMultioptions = [];
        //wybór z dostępnych języków
        foreach (\App\Registry::$config->languages as $lang) {
            if ($lang == \Mmi\App\FrontController::getInstance()->getRequest()->lang) {
                continue;
            }
            $langMultioptions[$lang] = $lang;
        }

        //źródło
        $this->addElementSelect('source')
            ->setLabel('Wybierz język źródłowy')
            ->setDescription('Brakujące klucze w bieżącym języku zostaną utworzone, wartości zostaną uzupełnione wartościami z języka źródłowego')
            ->setMultioptions($langMultioptions);

        $this->addElementSubmit('submit')
            ->setLabel('klonuj teksty');
    }

    /**
     * Zapis kluczy
     * @return boolean
     */
    public function beforeSave()
    {
        $lang = \Mmi\App\FrontController::getInstance()->getRequest()->lang;
        foreach (\Cms\Orm\CmsTextQuery::byLang($this->source)->find() as $record) {
            /* @var $record \Cms\Orm\CmsTextRecord */
            if (\Cms\Orm\CmsTextQuery::byKeyLang($record->key, $lang)->findFirst() !== null) {
                continue;
            }
            //nowy rekord
            $r = \Cms\Orm\CmsTextRecord();
            $r->lang = $lang;
            $r->key = $record->key;
            $r->content = $record->content;
            $r->save();
        }
        return true;
    }

}
