<?php

namespace Cms\Api;

/**
 * Template data object
 */
class TemplateDataTransport extends HttpJsonTransport
{
    public int      $id;
    public string   $template;
    public string   $name;
    public string   $dateAdd;
    public string   $dateModify;
    public string   $title;
    public string   $description;
    public bool     $opensNewWindow = false;
    public array    $attributes     = [];
    public array    $sections       = [];
    public array    $breadcrumbs    = [];
    public array    $siblings       = [];
    public array    $_links         = [];
}
