<?php

namespace Cms\Api;

/**
 * Widget transport object
 */
class AttachmentData implements DataInterface
{
    public string   $name;
    public string   $originalUrl;
    public string   $thumbUrl;
    public string   $thumb2xUrl;
    public string   $thumb4xUrl;
    public string   $size;
    public string   $mimeType;
    public int      $order;
    public array    $attributes;
}