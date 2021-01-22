<?php

namespace Cms\Transport;

/**
 * Template transport object
 */
class TemplateTransport implements TransportInterface
{
    public int $id;
    public array $attributes;
    public string $template;
    public string $name;
    public string $uri;
    public string $customUri;
    public string $dateAdd;
    public string $dateModify;
    public array $config;
    public array $sections;    
}