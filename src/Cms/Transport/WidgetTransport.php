<?php

namespace Cms\Transport;

/**
 * Widget transport object
 */
class WidgetTransport implements TransportInterface
{
    public string   $id;
    public string   $widget;
    public int      $order;
    public array    $attributes;
    public array    $files;
}