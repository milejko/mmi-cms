<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

/**
 * Kontroler statystyk
 */
class StatController extends Mvc\Controller
{

    /**
     * Filtracja i wyświetlanie statystyk
     */
    public function indexAction()
    {
        $year = $this->year ? $this->year : date('Y');
        $month = $this->month ? $this->month : date('m');
        //form filtrujący
        $form = new \CmsAdmin\Form\Stat\Object(null, ['object' => $this->object,
            'year' => $year,
            'month' => $month,
        ]);
        //przekazanie formularza
        $this->view->objectForm = $form;
        if ($form->isMine()) {
            //przekierowanie po odczycie pól z formularza
            if ($form->getElement('object')->getValue() && $form->getElement('month')->getValue() >= 1 && $form->getElement('month')->getValue() <= 12 && $form->getElement('year')->getValue() <= date('Y')) {
                $this->getResponse()->redirect('cmsAdmin', 'stat', 'index', ['object' => $form->getElement('object')->getValue(),
                    'year' => $form->getElement('year')->getValue(),
                    'month' => $form->getElement('month')->getValue(),
                ]);
            }
            //przekierowanie na dany miesiąc - bez zmiany obiektu
            $this->getResponse()->redirect('cmsAdmin', 'stat', 'index', ['year' => $form->getElement('year')->getValue(),
                'month' => $form->getValue('month'),
            ]);
        }
        if (!$this->object || !$this->year || !$this->month) {
            return;
        }
        $object = $this->object;
        $year = intval($year);
        $month = intval($month);
        $label = \Cms\Orm\CmsStatLabelQuery::byObject($object)
            ->findFirst();
        if ($label === null) {
            return;
        }
        $this->view->label = $label;
        //staty dzienne

        $prevMonth = ($month - 1) > 0 ? $month - 1 : 12;
        $prevYear = ($prevMonth == 12) ? $year - 1 : $year;
        $day = (ltrim(date('m'), '0') == $month) ? date('d') : date('t', strtotime($year . '-' . $month));

        //statystyki dzienne
        $this->view->dailyChart = \Cms\Model\StatFlot::getCode('dailyChart', [
                //bieżący miesiąc
                ['object' => $label->object,
                    'label' => $label->label . ': ' . $this->view->_('dni'),
                    'data' => \Cms\Model\Stat::toDate($object, null, $year, $month, $day)
                ],
                //poprzedni miesiąc
                ['object' => $label->object,
                    'label' => $this->view->_('Poprzedni miesiąc: dni'),
                    'data' => \Cms\Model\Stat::toDate($object, null, $prevYear, $prevMonth, $day)
                ]], 'lines', true);
        //statystyki miesięczne
        $this->view->monthlyChart = \Cms\Model\StatFlot::getCode('monthlyChart', [
                ['object' => $label->object,
                    'label' => $label->label . ': ' . $this->view->_('miesiące'),
                    'data' => \Cms\Model\Stat::monthly($object, null, $year)
                ]], 'bars');
        //statystyki roczne
        $this->view->yearlyChart = \Cms\Model\StatFlot::getCode('yearlyChart', [
                ['object' => $label->object,
                    'label' => $label->label . ': ' . $this->view->_('lata'),
                    'data' => \Cms\Model\Stat::yearly($object, null)
                ]], 'bars');
        //rozkład godzinowy
        $this->view->avgHourlyChart = \Cms\Model\StatFlot::getCode('avgHourlyChart', [
                ['object' => $label->object,
                    'label' => $label->label . ': ' . $this->view->_('rozkład godzinowy'),
                    'data' => \Cms\Model\Stat::avgHourly($object, null, $year, $month)
                ]], 'bars');
        //rozkład godzinowy
        $this->view->avgHourlyAllChart = \Cms\Model\StatFlot::getCode('avgHourlyAllChart', [
                ['object' => $label->object,
                    'label' => $label->label . ': ' . $this->view->_('rozkład ogólny'),
                    'data' => \Cms\Model\Stat::avgHourly($object, null, null, null)
                ]], 'bars');
    }

    /**
     * Zarządzanie labelkami
     */
    public function labelAction()
    {
        $this->view->grid = new \CmsAdmin\Plugin\StatLabelGrid();
    }

    /**
     * Edycja labelki
     */
    public function editAction()
    {
        $form = new \CmsAdmin\Form\Stat\Label(new \Cms\Orm\CmsStatLabelRecord($this->id));
        //jeśli form zapisany
        if ($form->isSaved()) {
            $this->getMessenger()->addMessage('Nazwa statystyki została zapisana', true);
            $this->getResponse()->redirect('cmsAdmin', 'stat', 'label');
        }
        $this->view->labelForm = $form;
    }

    /**
     * Usuwanie statystyki
     */
    public function deleteAction()
    {
        $label = (new \Cms\Orm\CmsStatLabelQuery)->findPk($this->id);
        if ($label && $label->delete()) {
            $this->getMessenger()->addMessage('Nazwa statystyki została usunięta', true);
        }
        $this->getResponse()->redirect('cmsAdmin', 'stat', 'label');
    }

}
