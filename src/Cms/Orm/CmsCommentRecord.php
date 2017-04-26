<?php

namespace Cms\Orm;

/**
 * Rekord komentarza
 */
class CmsCommentRecord extends \Mmi\Orm\Record
{

    public $id;
    public $cmsAuthId;
    public $parentId;
    public $dateAdd;
    public $title;
    public $text;
    public $signature;
    public $ip;
    public $stars;
    public $object;
    public $objectId;

    /**
     * Wstawienie rekordu
     * @return boolean
     */
    protected function _insert()
    {
        //data dodania
        $this->dateAdd = date('Y-m-d H:i:s');
        $this->signature = '~' . $this->signature;
        $this->ip = \Mmi\App\FrontController::getInstance()->getEnvironment()->remoteAddress;
        //dane z autoryzacji
        $auth = \App\Registry::$auth;
        if ($auth->hasIdentity()) {
            $this->signature = $auth->getUsername();
            $this->cmsAuthId = $auth->getId();
        }
        return parent::_insert();
    }

}
