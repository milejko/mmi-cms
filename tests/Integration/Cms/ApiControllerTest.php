<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2023 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Tests\Integration\Cms;

use Cms\ApiController;
use Mmi\App\AppTesting;
use Mmi\Http\Request;
use PHPUnit\Framework\TestCase;
use Tests\Mock\Cms\SampleCategoryData;

class ApiControllerTest extends TestCase
{
    private static ApiController $apiController;

    public static function setUpBeforeClass(): void
    {
        self::$apiController = AppTesting::$di->get(ApiController::class);
        SampleCategoryData::insertObjectsIntoDatabase();
    }

    public function testIfStructureIsProperlyRendered(): void
    {
        $response = self::$apiController->getContentsAction(new Request(['scope' => 'sample']));
        self::assertEquals(200, $response->getCode());
        self::assertEquals('application/json', $response->getType());
        $contentArray = json_decode($response->getContent(), true);
        self::assertCount(4, $contentArray['children']);
        $parentElement = $contentArray['children'][0];
        self::assertEquals('sample name (also a title)', $parentElement['name']);
        self::assertEquals('sample/sampletpl', $parentElement['template']);
        self::assertTrue($parentElement['visible']);
        self::assertFalse($parentElement['blank']);
        self::assertEquals(['some-attribute' => 'some-value'], $parentElement['attributes']);
        self::assertEquals('/api/sample/contents/sample-name-also-a-title', $parentElement['_links'][0]['href']);
        self::assertEquals('content', $parentElement['_links'][0]['rel']);

        $redirectElement = $contentArray['children'][1];
        self::assertEquals('Sample redirect', $redirectElement['name']);
        self::assertEquals('https://www.google.com', $redirectElement['_links'][0]['href']);
        self::assertEquals('external', $redirectElement['_links'][0]['rel']);

        self::assertCount(2, $parentElement['children']);

        $childElement = $parentElement['children'][0];
        self::assertEquals('another name', $childElement['name']);
        self::assertEquals('sample/sampletpl', $childElement['template']);
        self::assertEquals(1, $childElement['visible']);
        self::assertFalse($childElement['blank']);
        self::assertEquals(['some-other-attribute' => 'some-other-value'], $childElement['attributes']);
        self::assertEquals('/api/sample/contents/sample-name-also-a-title/another-name', $childElement['_links'][0]['href']);
        self::assertEquals('content', $childElement['_links'][0]['rel']);

        $secondChildElement = $parentElement['children'][1];
        self::assertEquals('yet another name', $secondChildElement['name']);
        self::assertEquals('sample/sampletpl', $secondChildElement['template']);
        self::assertFalse($secondChildElement['visible']);
        self::assertTrue($secondChildElement['blank']);
        self::assertEquals(['some-other-attribute' => 'some-other-value'], $secondChildElement['attributes']);
        self::assertEquals('/api/sample/contents/sample-name-also-a-title/yet-another-name', $secondChildElement['_links'][0]['href']);
        self::assertEquals('content', $secondChildElement['_links'][0]['rel']);
    }

    public function testIfInexistentPageGives404(): void
    {
        $response = self::$apiController->getCategoryAction(new Request(['scope' => 'sample', 'uri' => 'inexistent']));
        self::assertEquals(404, $response->getCode());
        self::assertEquals('application/json', $response->getType());
    }

    public function testIfMainElementIsProperlyRendered(): void
    {
        $response = self::$apiController->getCategoryAction(new Request(['scope' => 'sample', 'uri' => 'sample-name-also-a-title']));
        self::assertEquals(200, $response->getCode());
        self::assertEquals('application/json', $response->getType());

        $contentArray = json_decode($response->getContent(), true);
        self::assertNotNull($contentArray['id']);
        self::assertEquals('sample/sampletpl', $contentArray['template']);
        self::assertEquals('sample-name-also-a-title', $contentArray['path']);
        self::assertEquals('sample name (also a title)', $contentArray['name']);
        self::assertEquals(['some-attribute' => 'some-value'], $contentArray['attributes']);
        self::assertEquals('/api/sample/contents', $contentArray['_links'][0]['href']);
        self::assertEquals('contents', $contentArray['_links'][0]['rel']);
        self::assertTrue($contentArray['visible']);
        self::assertEquals('/api/sample/contents/sample-name-also-a-title', $contentArray['_links'][1]['href']);
        self::assertEquals('self', $contentArray['_links'][1]['rel']);

        self::assertEmpty($contentArray['breadcrumbs']);

        self::assertCount(2, $contentArray['children']);
        $firstChild = $contentArray['children'][0];
        self::assertEquals('another name', $firstChild['name']);
        self::assertEquals('/api/sample/contents/sample-name-also-a-title/another-name', $firstChild['_links'][0]['href']);
        self::assertEquals('content', $firstChild['_links'][0]['rel']);

        $firstChild = $contentArray['children'][1];
        self::assertEquals('yet another name', $firstChild['name']);
        self::assertEquals('/api/sample/contents/sample-name-also-a-title/yet-another-name', $firstChild['_links'][0]['href']);
        self::assertEquals('content', $firstChild['_links'][0]['rel']);
    }

    public function testIfFirstChildElementIsProperlyRendered(): void
    {
        $response = self::$apiController->getCategoryAction(new Request(['scope' => 'sample', 'uri' => 'sample-name-also-a-title/another-name']));
        self::assertEquals(200, $response->getCode());
        self::assertEquals('application/json', $response->getType());

        $contentArray = json_decode($response->getContent(), true);
        self::assertNotNull($contentArray['id']);
        self::assertEquals('sample/sampletpl', $contentArray['template']);
        self::assertEquals('sample-name-also-a-title/another-name', $contentArray['path']);
        self::assertEquals('another title', $contentArray['title']);
        self::assertEquals('description', $contentArray['description']);
        self::assertEquals(1, $contentArray['visible']);
        self::assertEquals(['some-other-attribute' => 'some-other-value'], $contentArray['attributes']);
        self::assertEmpty($contentArray['sections']);
        self::assertEmpty($contentArray['children']);
        self::assertCount(1, $contentArray['breadcrumbs']);

        $breadcrumb = $contentArray['breadcrumbs'][0];
        self::assertNotNull($breadcrumb['id']);
        self::assertEquals('sample name (also a title)', $breadcrumb['name']);
        self::assertEquals('sample-name-also-a-title', $breadcrumb['path']);
        self::assertEquals('sample/sampletpl', $breadcrumb['template']);
        self::assertEquals(false, $breadcrumb['blank']);
        self::assertTrue($breadcrumb['visible']);
        self::assertEquals('/api/sample/contents/sample-name-also-a-title', $breadcrumb['_links'][0]['href']);
    }

    public function testIfCategoryWithWidgetsIsProperlyRendered(): void
    {
        $response = self::$apiController->getCategoryAction(new Request(['scope' => 'sample', 'uri' => 'sample-name-also-a-title/yet-another-name']));
        self::assertEquals(200, $response->getCode());
        self::assertEquals('application/json', $response->getType());

        $contentArray = json_decode($response->getContent(), true);

        self::assertCount(1, $contentArray['sections']);
        self::assertCount(2, $contentArray['sections']['main']);
        self::assertEquals([
            ['id' => '11234-uuid', 'widget' => 'samplewidget', 'order' => 1, 'files' => [], 'attributes' => ['test' => 'value']],
            ['id' => '21234-uuid', 'widget' => 'anothersamplewidget', 'order' => 2, 'files' => [], 'attributes' => ['test2' => 'value2']],
        ], $contentArray['sections']['main']);

        self::assertNotNull($contentArray['id']);
        self::assertEquals('yet another name', $contentArray['name']);
        self::assertEquals('sample-name-also-a-title/yet-another-name', $contentArray['path']);
        self::assertEquals(['some-other-attribute' => 'some-other-value'], $contentArray['attributes']);
        self::assertEquals('sample/sampletpl', $contentArray['template']);
        self::assertEquals('description', $contentArray['description']);
        self::assertTrue($contentArray['opensNewWindow']);
        self::assertFalse($contentArray['visible']);
        self::assertEquals('/api/sample/contents', $contentArray['_links'][0]['href']);
        self::assertEquals('contents', $contentArray['_links'][0]['rel']);
        self::assertEquals('/api/sample/contents/sample-name-also-a-title/yet-another-name', $contentArray['_links'][1]['href']);
        self::assertEquals('self', $contentArray['_links'][1]['rel']);

        self::assertCount(1, $contentArray['children']);

        self::assertCount(1, $contentArray['breadcrumbs']);
        $breadcrumb = $contentArray['breadcrumbs'][0];
        self::assertEquals('sample name (also a title)', $breadcrumb['name']);
        self::assertEquals('/api/sample/contents/sample-name-also-a-title', $breadcrumb['_links'][0]['href']);
    }

    public function testIfCategoryCanBeReceivedByCustomUri(): void
    {
        $response = self::$apiController->getCategoryAction(new Request(['scope' => 'sample', 'uri' => 'get-me-by-this-address']));
        self::assertEquals(200, $response->getCode());
        self::assertEquals('application/json', $response->getType());

        $contentArray = json_decode($response->getContent(), true);
        self::assertEquals('unimportant', $contentArray['name']);
        self::assertEquals('get-me-by-this-address', $contentArray['path']);
        self::assertEmpty($contentArray['attributes']);
        self::assertEquals('sample/sampletpl', $contentArray['template']);
        self::assertEquals('/api/sample/contents', $contentArray['_links'][0]['href']);
        self::assertEquals('contents', $contentArray['_links'][0]['rel']);
        self::assertEquals('/api/sample/contents/get-me-by-this-address', $contentArray['_links'][1]['href']);
        self::assertEquals('self', $contentArray['_links'][1]['rel']);
    }

    public function testRedirectingUriToCustomUriIfPresent(): void
    {
        $response = self::$apiController->getCategoryAction(new Request(['scope' => 'sample', 'uri' => 'sample-name-also-a-title/unimportant']));
        self::assertEquals(301, $response->getCode());
        self::assertEquals('application/json', $response->getType());
        self::assertEquals('{"_links":[{"href":"\/api\/sample\/contents\/get-me-by-this-address","rel":"external","method":"REDIRECT"}]}', $response->getContent());
    }

    public function testIfHistoricalSlugWorksAsARedirect(): void
    {
        $response = self::$apiController->getCategoryAction(new Request(['scope' => 'sample', 'uri' => 'sample-name']));
        self::assertEquals(301, $response->getCode());
        self::assertEquals('application/json', $response->getType());
        self::assertEquals('{"_links":[{"href":"\/api\/sample\/contents\/sample-name-also-a-title","rel":"internal","method":"REDIRECT"}]}', $response->getContent());
    }

    public function testIfRedirectCategoryGivesProperRedirect(): void
    {
        $response = self::$apiController->getCategoryAction(new Request(['scope' => 'sample', 'uri' => 'sample-redirect']));
        self::assertEquals(301, $response->getCode());
        self::assertEquals('application/json', $response->getType());
        self::assertEquals('{"_links":[{"href":"https:\/\/www.google.com","rel":"external","method":"REDIRECT"}]}', $response->getContent());
    }

    public function testIfRecordWithInvalidTemplateCanNotBeReceived(): void
    {
        $response = self::$apiController->getCategoryAction(new Request(['scope' => 'sample', 'uri' => 'invalid']));
        self::assertEquals(404, $response->getCode());
    }

    public function testIfRecordWithBuggedTemplateCanNotBeReceived(): void
    {
        $response = self::$apiController->getCategoryAction(new Request(['scope' => 'sample', 'uri' => 'bugged-template']));
        self::assertEquals(404, $response->getCode());
        self::assertEquals('{"message":"some bug"}', $response->getContent());
    }

    public function testIfIdCanBeRedirectedToUri(): void
    {
        $response = self::$apiController->redirectIdAction(new Request(['id' => 1]));
        self::assertEquals(301, $response->getCode());
        self::assertEquals('application/json', $response->getType());
        self::assertEquals('{"_links":[{"href":"\/api\/sample\/contents\/sample-name-also-a-title","rel":"external","method":"REDIRECT"}]}', $response->getContent());
    }

    public function testIfInvalidTemplateRedirectGivesUnsupportedError(): void
    {
        $response = self::$apiController->redirectIdAction(new Request(['id' => 9]));
        self::assertEquals(404, $response->getCode());
        self::assertEquals('application/json', $response->getType());
        self::assertEquals('{"message":"Page unsupported"}', $response->getContent());
    }

    public function testIfIdCanNotBeRedirectedIfInactive(): void
    {
        $response = self::$apiController->redirectIdAction(new Request(['id' => 6]));
        self::assertEquals(404, $response->getCode());
        self::assertEquals('application/json', $response->getType());
        self::assertEquals('{"message":"Page not found"}', $response->getContent());
    }

    public function testPublishedCategoryPreview(): void
    {
        $response = self::$apiController->getCategoryPreviewAction(new Request(['scope' => 'sample', 'uri' => 'sample-name-also-a-title/yet-another-name']));
        self::assertEquals(200, $response->getCode());

        $contentArray = json_decode($response->getContent(), true);

        self::assertEquals('yet another name', $contentArray['name']);
        self::assertEquals('sample/sampletpl', $contentArray['template']);
        self::assertFalse($contentArray['visible']);
        self::assertEquals(['some-other-attribute' => 'some-other-value'], $contentArray['attributes']);
        self::assertEquals('/api/sample/contents', $contentArray['_links'][0]['href']);
        self::assertEquals('contents', $contentArray['_links'][0]['rel']);
        self::assertEquals('/api/sample/contents/preview/sample-name-also-a-title/yet-another-name', $contentArray['_links'][1]['href']);
        self::assertEquals('self', $contentArray['_links'][1]['rel']);
    }

    public function testDraftCategoryPreview(): void
    {
        $response = self::$apiController->getCategoryPreviewAction(new Request(['scope' => 'sample', 'id' => 8, 'originalId' => 1, 'authId' => 1]));
        self::assertEquals(200, $response->getCode());
        $contentArray = json_decode($response->getContent(), true);
        self::assertEquals('sample name', $contentArray['name']);
        self::assertEquals('sample/sampletpl', $contentArray['template']);
        self::assertEquals('sample-name', $contentArray['path']);
        self::assertEmpty($contentArray['attributes']);
        self::assertCount(2, $contentArray['children']);
        self::assertEmpty($contentArray['breadcrumbs']);
        self::assertEquals('/api/sample/contents/preview/sample-name', $contentArray['_links'][1]['href']);
        self::assertEquals('self', $contentArray['_links'][1]['rel']);
    }

    public function testDraftCategoryPreviewWithMessedUpParams(): void
    {
        $response = self::$apiController->getCategoryPreviewAction(new Request(['scope' => 'sample', 'id' => 8, 'originalId' => 1, 'authId' => 2]));
        self::assertEquals(404, $response->getCode());
        $response = self::$apiController->getCategoryPreviewAction(new Request(['scope' => 'sample', 'id' => 8, 'originalId' => 2, 'authId' => 2]));
        self::assertEquals(404, $response->getCode());
        $response = self::$apiController->getCategoryPreviewAction(new Request(['scope' => 'sample', 'id' => 7, 'originalId' => 1, 'authId' => 2]));
        self::assertEquals(404, $response->getCode());
    }
}
