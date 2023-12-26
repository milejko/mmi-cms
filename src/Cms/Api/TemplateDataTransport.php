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
    public string   $path;
    public string   $dateAdd;
    public ?string  $dateModify;
    public string   $title;
    public string   $description;
    public string   $ogImageUrl;
    public string   $order;
    public bool     $opensNewWindow = false;
    public bool     $visible        = true;
    public array    $attributes     = [];
    public array    $sections       = [];
    public array    $children       = [];
    public array    $siblings       = [];
    public array    $breadcrumbs    = [];
    public array    $_links         = [];
}
