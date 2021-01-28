<?php

namespace Cms\Api;

/**
 * Link data object
 */
class LinkData implements DataInterface
{
    const METHOD_GET    = 'GET';
    const METHOD_PUT    = 'PUT';
    const METHOD_POST   = 'POST';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PATCH  = 'PATCH';

    public string   $href;
    public string   $rel;
    public string   $method = self::METHOD_GET;
    public string   $title;
}