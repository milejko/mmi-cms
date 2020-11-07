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
use Mmi\App\App;
use Mmi\Cache\Cache;

class Text
{
    const CACHE_PREFIX      = 'cms-text';
    const UNKNOWN_LANGUAGE  = 'unknown';

    /**
     * Teksty w roznych jezykach
     * @var array
     */
    protected static $_texts = [];

    /**
     * 
     * @param string $key
     * @param string $lang
     * @return string|null
     */
    public static function textByKeyLang($key, $lang)
    {
        if (empty(self::$_texts)) {
            self::_initDictionary();
        }
        if ($lang === null) {
            $lang = self::UNKNOWN_LANGUAGE;
        }
        if (isset(self::$_texts[$lang][$key])) {
            return self::$_texts[$lang][$key];
        }
        if (isset(self::$_texts[self::UNKNOWN_LANGUAGE][$key])) {
            return self::$_texts[self::UNKNOWN_LANGUAGE][$key];
        }
        return null;
    }

    /**
     * Inicjalizacja slownika
     */
    protected static function _initDictionary()
    {
        if (null === (self::$_texts = App::$di->get(Cache::class)->load(self::CACHE_PREFIX))) {
            self::$_texts = [];
            foreach ((new Orm\CmsTextQuery)->find() as $text) {
                if ($text->lang === null) {
                    self::$_texts[self::UNKNOWN_LANGUAGE][$text->key] = $text->content;
                    continue;
                }
                self::$_texts[$text->lang][$text->key] = $text->content;
            }
            App::$di->get(Cache::class)->save(self::$_texts, self::CACHE_PREFIX, 0);
        }
    }

}
