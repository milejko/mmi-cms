<?php

namespace Cms\Api;

interface TransportInterface
{
    public function getCode(): int;
    public function toString(): string;
}