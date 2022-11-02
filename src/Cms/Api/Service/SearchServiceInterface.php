<?php

namespace Cms\Api\Service;

use Mmi\Http\Request;

interface SearchServiceInterface
{
    public function getResult(Request $request): array;

    public function getTotal(Request $request): int;
}
