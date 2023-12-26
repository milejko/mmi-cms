<?php

namespace Cms\Api\Service;

interface MenuServiceInterface
{
    public function setFormatter(?string $formatterName = null): self;

    public function getMenus(?string $scope, int $maxLevel = 0): array;
}
