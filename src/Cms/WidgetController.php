<?php

namespace Cms;

use Mmi\Mvc\Controller;

/**
 * Abstrakcyjna klasa kontrolera widgetów
 */
abstract class WidgetController extends Controller
{

    //edycja widgeta
    abstract public function editAction();

    //podgląd w admin panelu
    abstract public function previewAction();

    //wyświetlenie po stronie klienta
    abstract public function displayAction();
    
}