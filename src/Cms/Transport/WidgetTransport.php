<?php

namespace Cms\Transport;

/**
 * Widget transport object
 */
class WidgetTransport implements TransportInterface
{
    public string   $id;
    public string   $widget;
    public array    $config;
    public int      $order;
    public array    $attachments;
}