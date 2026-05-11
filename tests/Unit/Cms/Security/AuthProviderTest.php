<?php

namespace Tests\Unit\Cms\Security;

use Cms\Orm\CmsAuthRecord;
use Cms\Security\AuthProvider;
use Mmi\Http\Request;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class AuthProviderTest extends TestCase
{
    private LoggerInterface $logger;
    private ContainerInterface $container;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->container = $this->createMock(ContainerInterface::class);
    }

    private function createProvider(string $remoteAddr = '127.0.0.1'): AuthProvider
    {
        $request = new Request([], [], [], [], ['REMOTE_ADDR' => $remoteAddr]);
        return new AuthProvider($this->logger, $request, $this->container);
    }

    private function createProviderWithXForwardedFor(string $xff): AuthProvider
    {
        $request = new Request([], [], [], [], ['HTTP_X_FORWARDED_FOR' => $xff]);
        return new AuthProvider($this->logger, $request, $this->container);
    }

    /**
     * @dataProvider extractFirstIpDataProvider
     */
    public function testExtractFirstIp($input, string $expected): void
    {
        $provider = $this->createProvider();

        $reflection = new \ReflectionMethod($provider, 'extractFirstIp');
        $reflection->setAccessible(true);

        self::assertSame($expected, $reflection->invoke($provider, $input));
    }

    public static function extractFirstIpDataProvider(): array
    {
        return [
            'single IP string' => ['192.168.1.1', '192.168.1.1'],
            'comma-separated IPs' => ['192.168.1.1, 10.0.0.1', '192.168.1.1'],
            'comma-separated with extra spaces' => [' 192.168.1.1 , 10.0.0.1 ', '192.168.1.1'],
            'three comma-separated IPs' => ['1.2.3.4, 5.6.7.8, 9.10.11.12', '1.2.3.4'],
            'array with single IP' => [['192.168.1.1'], '192.168.1.1'],
            'array with multiple IPs' => [['192.168.1.1', '10.0.0.1'], '192.168.1.1'],
            'array with spaces' => [[' 192.168.1.1 '], '192.168.1.1'],
            'empty string' => ['', ''],
            'empty array' => [[], ''],
            'null value' => [null, ''],
            'IPv6 address' => ['::1', '::1'],
            'comma-separated IPv6' => ['2001:db8::1, ::1', '2001:db8::1'],
            'single IP no spaces' => ['10.0.0.1', '10.0.0.1'],
        ];
    }

    public function testAuthSuccessSetsFirstIpFromCommaSeparatedList(): void
    {
        $provider = $this->createProviderWithXForwardedFor('203.0.113.1, 198.51.100.1, 192.0.2.1');

        $record = $this->createMock(CmsAuthRecord::class);
        $record->id = 1;
        $record->username = 'testuser';
        $record->name = 'Test User';
        $record->email = 'test@example.com';
        $record->lang = 'en';
        $record->roles = 'admin';
        $record->method('getRoles')->willReturn(['admin']);
        $record->expects(self::once())->method('save');

        $reflection = new \ReflectionMethod($provider, '_authSuccess');
        $reflection->setAccessible(true);

        $authRecord = $reflection->invoke($provider, $record);

        self::assertSame('203.0.113.1', $record->lastIp);
        self::assertNotNull($record->lastLog);
        self::assertSame(1, $authRecord->id);
        self::assertSame('testuser', $authRecord->username);
        self::assertSame('Test User', $authRecord->name);
        self::assertSame('test@example.com', $authRecord->email);
        self::assertSame('en', $authRecord->lang);
        self::assertSame(['admin'], $authRecord->roles);
    }

    public function testAuthSuccessSetsPlainIp(): void
    {
        $provider = $this->createProvider('10.20.30.40');

        $record = $this->createMock(CmsAuthRecord::class);
        $record->id = 5;
        $record->username = 'admin';
        $record->name = 'Admin';
        $record->email = 'admin@example.com';
        $record->lang = 'pl';
        $record->roles = 'editor';
        $record->method('getRoles')->willReturn(['editor']);
        $record->method('save');

        $reflection = new \ReflectionMethod($provider, '_authSuccess');
        $reflection->setAccessible(true);

        $authRecord = $reflection->invoke($provider, $record);

        self::assertSame('10.20.30.40', $record->lastIp);
    }

    public function testAuthSuccassAssignsGuestRoleWhenNoRoles(): void
    {
        $provider = $this->createProvider();

        $record = $this->createMock(CmsAuthRecord::class);
        $record->id = 2;
        $record->username = 'noroles';
        $record->name = 'No Roles';
        $record->email = 'noroles@example.com';
        $record->lang = 'en';
        $record->roles = '';
        $record->method('getRoles')->willReturn([]);
        $record->method('save');

        $reflection = new \ReflectionMethod($provider, '_authSuccess');
        $reflection->setAccessible(true);

        $authRecord = $reflection->invoke($provider, $record);

        self::assertSame(['guest'], $authRecord->roles);
    }

    public function testUpdateUserFailedLoginSetsFirstIpFromCommaSeparatedList(): void
    {
        $provider = $this->createProviderWithXForwardedFor('198.51.100.5, 10.0.0.1');

        $record = $this->createMock(CmsAuthRecord::class);
        $record->failLogCount = 3;
        $record->expects(self::once())->method('save');

        $reflection = new \ReflectionMethod($provider, '_updateUserFailedLogin');
        $reflection->setAccessible(true);

        $reflection->invoke($provider, $record);

        self::assertSame('198.51.100.5', $record->lastFailIp);
        self::assertNotNull($record->lastFailLog);
        self::assertSame(4, $record->failLogCount);
    }

    public function testUpdateUserFailedLoginSetsPlainIp(): void
    {
        $provider = $this->createProvider('172.16.0.1');

        $record = $this->createMock(CmsAuthRecord::class);
        $record->failLogCount = 0;
        $record->method('save');

        $reflection = new \ReflectionMethod($provider, '_updateUserFailedLogin');
        $reflection->setAccessible(true);

        $reflection->invoke($provider, $record);

        self::assertSame('172.16.0.1', $record->lastFailIp);
        self::assertSame(1, $record->failLogCount);
    }

    public function testUpdateUserFailedLoginIncrementsCounter(): void
    {
        $provider = $this->createProvider();

        $record = $this->createMock(CmsAuthRecord::class);
        $record->failLogCount = 10;
        $record->method('save');

        $reflection = new \ReflectionMethod($provider, '_updateUserFailedLogin');
        $reflection->setAccessible(true);

        $reflection->invoke($provider, $record);

        self::assertSame(11, $record->failLogCount);
    }

    public function testGetSaltedPasswordHash(): void
    {
        $this->container->method('get')
            ->with('cms.auth.salt')
            ->willReturn('test-salt');

        $provider = $this->createProvider();

        $hash = $provider->getSaltedPasswordHash('mypassword');

        self::assertSame(
            hash('sha512', 'test-salt' . md5('mypassword') . 'mypassword' . 'sltd'),
            $hash
        );
    }

    public function testGetSaltedPasswordHashDifferentPasswords(): void
    {
        $this->container->method('get')
            ->with('cms.auth.salt')
            ->willReturn('salt');

        $provider = $this->createProvider();

        $hash1 = $provider->getSaltedPasswordHash('password1');
        $hash2 = $provider->getSaltedPasswordHash('password2');

        self::assertNotSame($hash1, $hash2);
    }

    public function testAuthSuccessLogsUsername(): void
    {
        $this->logger->expects(self::once())
            ->method('info')
            ->with('Logged in: adminuser');

        $provider = $this->createProvider();

        $record = $this->createMock(CmsAuthRecord::class);
        $record->id = 1;
        $record->username = 'adminuser';
        $record->name = 'Admin';
        $record->email = 'admin@example.com';
        $record->lang = 'en';
        $record->roles = 'admin';
        $record->method('getRoles')->willReturn(['admin']);
        $record->method('save');

        $reflection = new \ReflectionMethod($provider, '_authSuccess');
        $reflection->setAccessible(true);

        $reflection->invoke($provider, $record);
    }

    public function testAuthFailedLogsIdentityAndReason(): void
    {
        $this->logger->expects(self::once())
            ->method('notice')
            ->with('Login failed: baduser Invalid password.');

        $provider = $this->createProvider();

        $reflection = new \ReflectionMethod($provider, '_authFailed');
        $reflection->setAccessible(true);

        $reflection->invoke($provider, 'baduser', 'Invalid password.');
    }

    public function testDeauthenticateDoesNothing(): void
    {
        $provider = $this->createProvider();
        $provider->deauthenticate();

        // deauthenticate is a no-op, just verify it doesn't throw
        self::assertTrue(true);
    }
}
