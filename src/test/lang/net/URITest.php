<?php

declare(strict_types=1);

namespace vinyl\stdTest\lang\net;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use vinyl\std\net\URI;

class URITest extends TestCase
{
    /**
     * @return array<array<string|array<string, int|string>>>
     */
    public static function validURIProvider(): array
    {
        return [
            [
                'ftp://ftp.is.co.za/rfc/rfc1808.txt',
                [
                    'scheme' => 'ftp',
                    'host'   => 'ftp.is.co.za',
                    'path'   => '/rfc/rfc1808.txt'
                ]
            ],
            [
                'http://www.ics.uci.edu/pub/ietf/uri/historical.html#WARNING',
                [
                    'scheme'   => 'http',
                    'host'     => 'www.ics.uci.edu',
                    'path'     => '/pub/ietf/uri/historical.html',
                    'fragment' => 'WARNING'
                ]
            ],
            [
                'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top',
                [
                    'scheme'   => 'https',
                    'user'     => 'john.doe',
                    'host'     => 'www.example.com',
                    'port'     => 123,
                    'path'     => '/forum/questions/',
                    'query'    => 'tag=networking&order=newest',
                    'fragment' => 'top'
                ]
            ],
            [
                'ldap://[2001:db8::7]/c=GB?objectClass?one',
                [
                    'scheme'    => 'ldap',
                    'authority' => '[2001:db8::7]',
                    'path'      => '/c=GB',
                    'query'     => 'objectClass?one'
                ]
            ],
            [
                'telnet://192.0.2.16:80/',
                [
                    'scheme'    => 'telnet',
                    'authority' => '192.0.2.16:80',
                    'path'      => '/',
                ]
            ],
            [
                'mailto:John.Doe@example.com',
                ['scheme' => 'mailto', 'path' => 'John.Doe@example.com']
            ]
        ];
    }

    #[DataProvider('validURIProvider')]
    #[Test]
    public function validUri(string $uri, array $parts): void
    {
        $uriObject = URI::create($uri);
        /** @var int|string $expectedValue */
        foreach ($parts as $method => $expectedValue) {
            self::assertSame($expectedValue, $uriObject->$method());
        }

        self::assertSame($uri, (string)$uriObject);
    }
}
