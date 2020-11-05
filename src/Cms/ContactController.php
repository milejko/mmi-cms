<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

use Mmi\Http\HttpServerEnv;
use Mmi\Http\Request;

/**
 * Strona kontaktowa
 */
class ContactController extends \Mmi\Mvc\Controller
{

    /**
     * @Inject
     * @var HttpServerEnv
     */
    private $httpServerEnv;

    /**
     * Akcja kontaktu
     */
    public function indexAction(Request $request)
    {
        //ciasteczko sesyjne - zapamietanie sciezki
        $namespace = new \Mmi\Session\SessionSpace('contact');
        //formularz kontaktowy z rekordem kontaktu
        $form = new \Cms\Form\Contact(new \Cms\Orm\CmsContactRecord(), [
            'subjectId' => $request->subjectId
        ]);
        //do widoku
        $this->view->contactForm = $form;
        //zapis
        if ($form->isSaved()) {
            $this->getMessenger()->addMessage('messenger.contact.message.sent', true);
            if ($namespace->referer) {
                $link = $namespace->referer;
            } else {
                $link = $this->view->url();
            }
            $namespace->unsetAll();
            $this->getResponse()->redirectToUrl($link);
        } elseif ($this->httpServerEnv->httpReferer) {
            $namespace->referer = $this->httpServerEnv->httpReferer;
        }
    }

}
