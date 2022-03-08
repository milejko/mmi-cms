<?php

namespace Cms\Api;

/**
 * Skin data object
 */
class SkinData implements DataInterface
{
    public string   $key;
    public string   $name;
    public array    $_links = [];
}
