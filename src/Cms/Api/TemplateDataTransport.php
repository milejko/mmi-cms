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
    public string   $url;
    public string   $dateAdd;
    public string   $dateModify;
    public array    $attributes = [];
    public array    $sections   = [];
    public array    $_links     = [];
}