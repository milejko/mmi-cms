<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2019 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

class CacheOptions
{
    public const LIFETIMES = [
        2592000 => 'model.cache.lifetime.2592000',
        0 => 'model.cache.lifetime.0',
        60 => 'model.cache.lifetime.60',
        300 => 'model.cache.lifetime.300',
        600 => 'model.cache.lifetime.600',
        3600 => 'model.cache.lifetime.3600',
        28800 => 'model.cache.lifetime.28800',
        86400 => 'model.cache.lifetime.86400',
    ];
}
