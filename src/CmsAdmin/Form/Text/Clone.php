<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form\Text;

use Mmi\App\App;
use Mmi\Http\Request;

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
            if ($lang == App::$di->get(Request::class)->lang) {
                continue;
            }
            $langMultioptions[$lang] = $lang;
        }

        //źródło
        $this->addElement((new Element\Select('source'))
            ->setLabel('form.text.copy.source.label')
            ->setDescription('form.text.copy.source.description')
            ->setMultioptions($langMultioptions));

        $this->addElement((new Element\Submit('submit'))
            ->setLabel('form.text.copy.submit.label'));
    }

    /**
     * Zapis kluczy
     * @return boolean
     */
    public function beforeSave()
    {
        $lang = App::$di->get(Request::class)->lang;
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
