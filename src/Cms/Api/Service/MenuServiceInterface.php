<?php

namespace Cms\Api\Service;

interface MenuServiceInterface
{
    public function getMenus(?string $scope, int $maxLevel = 0): array;
}
