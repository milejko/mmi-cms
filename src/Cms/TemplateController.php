<?php

namespace Cms;

use Mmi\Mvc\Controller;

/**
 * Abstrakcyjna klasa kontrolera widgetów
 */
abstract class TemplateController extends Controller
{

    //wyświetlenie po stronie klienta
    abstract public function displayAction();
    
}