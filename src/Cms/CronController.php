<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

/**
 * Kontroler harmonogramu zadań
 */
class CronController extends \Mmi\Mvc\Controller
{

    /**
     * Uruchomienie crona
     * @return string
     */
    public function indexAction()
    {
        \Cms\Model\Cron::run();
        return 'OK';
    }

    /**
     * Wysyłka maili
     */
    public function sendMailAction()
    {
        if (rand(0, 120) == 12) {
            $this->view->cleared = \Cms\Model\Mail::clean();
        }
        $this->view->result = \Cms\Model\Mail::send();
    }

    /**
     * Agregator statystyk
     */
    public function agregateAction()
    {
        $this->view->result = \Cms\Model\Stat::agregate();
    }

    /**
     * Czyściciel logów
     */
    public function cleanAction()
    {
        $months = 12;
        if ($this->months > 0) {
            $months = intval($this->months);
        }
        //czyszczenie logów
        \Cms\Model\Log::clean($months);
        return '';
    }
    
    /**
     * Usuwa pliki tymczasowe Cms File
     */
    public function deleteOrphansAction()
    {
        //usuwanie plików tmp
        \Cms\Model\File::deleteOrphans();
        return '';
    }

}
