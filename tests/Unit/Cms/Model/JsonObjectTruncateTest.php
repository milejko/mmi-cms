<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2023 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Tests\Unit\Cms\Model;

use Cms\Model\JsonObjectTruncate;
use Mmi\Validator\ValidatorException;
use PHPUnit\Framework\TestCase;

class JsonObjectTruncateTest extends TestCase
{
    private const SAMPLE_JSON = '{
        "attributes": {
            "some boolean": true,
            "some number": 1234567890,
            "some plaintext": "some plain text with no HTML",
            "some html": "<p>paragraph \r\n<h1>h1 header<\/h1> <strong>strong   text<\/strong> end of paragraph<\/p> <a href=\"#\">link<\/a>",
            "nested": {
                "another plaintext": "another plain text with no HTML",
                "another html": "<a href=\"https:\/\/www.google.com\" target=\"_blank\">a link to <strong>google<\/strong><\/a>"
            }
        }
    }';

    private const INVALID_JSON = '{"not a json": }';

    public function testIfBrokenJsonProducesAnException(): void
    {
        $this->expectException(ValidatorException::class);

        (new JsonObjectTruncate())
            ->setInputFromJsonString(self::INVALID_JSON)
            ->getAsArray(160);
    }

    public function testIfJsonStructureIsMaintained(): void
    {
        $truncatedJsonArray = (new JsonObjectTruncate())
            ->setInputFromJsonString(self::SAMPLE_JSON)
            ->getAsArray();

        self::assertArrayHasKey('attributes', $truncatedJsonArray);
        self::assertArrayHasKey('some boolean', $truncatedJsonArray['attributes']);
        self::assertArrayHasKey('some number', $truncatedJsonArray['attributes']);
        self::assertArrayHasKey('some plaintext', $truncatedJsonArray['attributes']);
        self::assertArrayHasKey('some html', $truncatedJsonArray['attributes']);
        self::assertArrayHasKey('nested', $truncatedJsonArray['attributes']);
        self::assertArrayHasKey('another plaintext', $truncatedJsonArray['attributes']['nested']);
        self::assertArrayHasKey('another html', $truncatedJsonArray['attributes']['nested']);
    }

    public function testIfBooleansAndNumbersAreMaintained(): void
    {
        $truncatedJsonArray = (new JsonObjectTruncate())
            ->setInputFromJsonString(self::SAMPLE_JSON)
            ->getAsArray(0);

        self::assertTrue($truncatedJsonArray['attributes']['some boolean']);
        self::assertEquals(1234567890, $truncatedJsonArray['attributes']['some number']);
        self::assertEquals('', $truncatedJsonArray['attributes']['some plaintext']);
    }

    public function testIfJsonInsideGetsSanitized(): void
    {
        $truncatedJsonArray = (new JsonObjectTruncate())
            ->setInputFromJsonString(self::SAMPLE_JSON)
            ->getAsArray(160);

        self::assertEquals('paragraph h1 header strong text end of paragraph link', $truncatedJsonArray['attributes']['some html']);
        self::assertEquals('a link to google', $truncatedJsonArray['attributes']['nested']['another html']);
    }

    public function testIfInsideGetsTruncated(): void
    {
        $truncatedJsonArray = (new JsonObjectTruncate())
            ->setInputFromJsonString(self::SAMPLE_JSON)
            ->getAsArray(9);

        self::assertEquals('a link to', $truncatedJsonArray['attributes']['nested']['another html']);
    }
}
