<?php

namespace Cms\Api;

/**
 * Attachment data object
 */
class AttachmentData implements DataInterface
{
    public string   $name;
    public string   $fileName;
    public string   $size;
    public string   $mimeType;
    public int      $order      = 0;
    public array    $attributes = [];
}
