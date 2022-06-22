<?php

namespace Cms\Api;

/**
 * Skin config transport object
 */
class SkinConfigTransport extends HttpJsonTransport
{
    public string   $key;
    public array    $attributes = [];
    public array    $templates = [];
    public array    $_links = [];
}
