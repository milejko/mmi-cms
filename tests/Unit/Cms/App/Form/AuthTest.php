<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Tests\Unit\Cms\App;

use Cms\Orm\CmsAuthRecord;
use CmsAdmin\Form\Auth;
use PHPUnit\Framework\TestCase;

/**
 * Klasa konfiguracji routera
 */
class AuthTest extends TestCase
{
    public function testIfFormIsProperlyBuilt(): void
    {
        $userRecord = new CmsAuthRecord();
        $userRecord->id = 13;
        $authForm = new Auth($userRecord, []);
        self::assertEquals(13, $authForm->getRecord()->id);
        self::assertEquals('cmsadmin-form-auth[username]', $authForm->getElement('username')->getName());
        self::assertCount(2, $authForm->getElement('username')->getValidators());
        self::assertEquals('cmsadmin-form-auth[name]', $authForm->getElement('name')->getName());
        self::assertEmpty($authForm->getElement('name')->getValidators());
        self::assertEquals('cmsadmin-form-auth[email]', $authForm->getElement('email')->getName());
        self::assertCount(2, $authForm->getElement('email')->getValidators());
        self::assertTrue($authForm->getElement('email')->getRequired());
        self::assertEquals('cmsadmin-form-auth[cmsRoles]', $authForm->getElement('cmsRoles')->getName());
        self::assertCount(1, $authForm->getElement('cmsRoles')->getValidators());
        self::assertEquals('cmsadmin-form-auth[active]', $authForm->getElement('active')->getName());
        self::assertEmpty($authForm->getElement('active')->getValidators());
        self::assertEquals('cmsadmin-form-auth[changePassword]', $authForm->getElement('changePassword')->getName());
        self::assertCount(1, $authForm->getElement('changePassword')->getValidators());
        self::assertEquals('cmsadmin-form-auth[submit]', $authForm->getElement('submit')->getName());
    }
}
