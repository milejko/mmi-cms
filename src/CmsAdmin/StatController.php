<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use Mmi\Http\Request;
use Mmi\Mvc\Controller;

/**
 * Kontroler statystyk
 */
class StatController extends Controller
{

    /**
     * Filtracja i wyświetlanie statystyk
     */
    public function indexAction(Request $request)
    {
        $year = $request->year ? $request->year : date('Y');
        $month = $request->month ? $request->month : date('m');
        //form filtrujący
        $form = new \CmsAdmin\Form\Stat\StatObject(null, ['object' => $request->object,
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
        if (!$request->object || !$request->year || !$request->month) {
            return;
        }
        $object = $request->object;
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
                    'label' => $label->label . ': ' . $this->view->_('controller.stat.index.days'),
                    'data' => \Cms\Model\Stat::toDate($object, null, $year, $month, $day)
                ],
                //poprzedni miesiąc
                ['object' => $label->object,
                    'label' => $this->view->_('controller.stat.index.previosMonthDays'),
                    'data' => \Cms\Model\Stat::toDate($object, null, $prevYear, $prevMonth, $day)
                ]], 'lines', true);
        //statystyki miesięczne
        $this->view->monthlyChart = \Cms\Model\StatFlot::getCode('monthlyChart', [
                ['object' => $label->object,
                    'label' => $label->label . ': ' . $this->view->_('controller.stat.index.months'),
                    'data' => \Cms\Model\Stat::monthly($object, null, $year)
                ]], 'bars');
        //statystyki roczne
        $this->view->yearlyChart = \Cms\Model\StatFlot::getCode('yearlyChart', [
                ['object' => $label->object,
                    'label' => $label->label . ': ' . $this->view->_('controller.stat.index.years'),
                    'data' => \Cms\Model\Stat::yearly($object, null)
                ]], 'bars');
        //rozkład godzinowy
        $this->view->avgHourlyChart = \Cms\Model\StatFlot::getCode('avgHourlyChart', [
                ['object' => $label->object,
                    'label' => $label->label . ': ' . $this->view->_('controller.stat.index.hourDistribution'),
                    'data' => \Cms\Model\Stat::avgHourly($object, null, $year, $month)
                ]], 'bars');
        //rozkład godzinowy
        $this->view->avgHourlyAllChart = \Cms\Model\StatFlot::getCode('avgHourlyAllChart', [
                ['object' => $label->object,
                    'label' => $label->label . ': ' . $this->view->_('controller.stat.index.generalDistribution'),
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
    public function editAction(Request $request)
    {
        $form = new \CmsAdmin\Form\Stat\Label(new \Cms\Orm\CmsStatLabelRecord($request->id));
        //jeśli form zapisany
        if ($form->isSaved()) {
            $this->getMessenger()->addMessage('messenger.stat.saved', true);
            $this->getResponse()->redirect('cmsAdmin', 'stat', 'label');
        }
        $this->view->labelForm = $form;
    }

    /**
     * Usuwanie statystyki
     */
    public function deleteAction(Request $request)
    {
        $label = (new \Cms\Orm\CmsStatLabelQuery)->findPk($request->id);
        if ($label && $label->delete()) {
            $this->getMessenger()->addMessage('messenger.stat.deleted', true);
        }
        $this->getResponse()->redirect('cmsAdmin', 'stat', 'label');
    }

}
