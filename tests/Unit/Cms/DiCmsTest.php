<?php

declare(strict_types=1);

namespace Tests\Unit\Cms;

use Cms\App\CmsScopeConfig;
use Mmi\App\AppEventInterceptorInterface;
use Mmi\Security\AuthProviderInterface;
use PHPUnit\Framework\TestCase;

final class DiCmsTest extends TestCase
{
    public function testIfDiFileContainsCrucialServices(): void
    {
        $diDefinitons = include('./src/Cms/di.cms.php');
        self::assertArrayHasKey(AppEventInterceptorInterface::class, $diDefinitons);
        self::assertArrayHasKey(AuthProviderInterface::class, $diDefinitons);
        self::assertArrayHasKey(CmsScopeConfig::class, $diDefinitons);
    }
}
