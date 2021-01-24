<?php

namespace Cms\Transport;

/**
 * Template transport object
 */
class TemplateTransport implements TransportInterface
{
    public int      $id;
    public string   $template;
    public string   $name;
    public string   $url;
    public string   $dateAdd;
    public string   $dateModify;
    public array    $attributes;
    public array    $sections;    
}