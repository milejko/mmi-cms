<?php

namespace Cms\Api;

/**
 * Widget data object
 */
class WidgetData implements DataInterface
{
    public string   $id;
    public string   $widget;
    public int      $order;
    public array    $files      = [];
    public array    $attributes = [];
}
