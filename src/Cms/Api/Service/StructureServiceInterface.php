<?php

namespace Cms\Api\Service;

interface StructureServiceInterface
{
    public function getStructure(?string $scope): array;
}
