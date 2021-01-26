<?php

namespace Cms\Transport;

/**
 * Widget transport object
 */
class AttachmentTransport implements TransportInterface
{
    public string   $name;
    public string   $url;
    public string   $size;
    public string   $mimeType;
    public int      $order;
    public array    $attributes;
}