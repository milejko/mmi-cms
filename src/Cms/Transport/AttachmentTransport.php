<?php

namespace Cms\Transport;

/**
 * Widget transport object
 */
class AttachmentTransport implements TransportInterface
{
    public string $name;
    public string $size;
    public string $mimeType;
    public int $order;
    public array $meta;
}