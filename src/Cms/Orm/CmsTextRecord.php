<?php

namespace Cms\Orm;

use Mmi\App\App;
use Mmi\Cache\CacheInterface;
use Mmi\Http\Request;

/**
 * Rekord tekstu
 */
class CmsTextRecord extends \Mmi\Orm\Record
{

    public $id;
    public $lang;
    public $key;
    public $content;
    public $dateModify;

    /**
     * Zapis rekordu
     * @return boolean
     */
    public function save()
    {
        //data modyfikacji
        $this->dateModify = date('Y-m-d H:i:s');
        $this->lang = App::$di->get(Request::class)->lang;
        //usunięcie kompilantów
        foreach (glob(BASE_PATH . '/var/compile/' . $this->lang . '_*.php') as $compilant) {
            unlink($compilant);
        }
        try {
            $result = parent::save();
        } catch (\Exception $e) {
            //duplikat
            return false;
        }
        //usunięcie cache
        App::$di->get(CacheInterface::class)->remove('Cms-text');
        return $result;
    }

}
