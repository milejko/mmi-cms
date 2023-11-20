<?php

namespace Cms\Api;

use Cms\App\CmsRouterConfig;
use Cms\Orm\CmsCategoryQuery;

/**
 * Redirect transport object
 */
class RedirectTransport extends HttpJsonTransport
{
    public const DEFAULT_CODE   = 301;

    protected int     $code   = self::DEFAULT_CODE;
    public array   $_links = [];

    public function __construct(string $link)
    {
        $this->setHref($link);
    }

    public function setHref(string $href): self
    {
        $redirectType = LinkData::REL_EXTERNAL;
        if (preg_match('/^' . str_replace('/', '\/', LinkData::INTERNAL_REDIRECT_PREFIX) . '(\d+)/', $href, $matches)) {
            $redirectType = LinkData::REL_INTERNAL;
            $cmsCategoryRecord = (new CmsCategoryQuery())->findPk($matches[1]);
            $href = $cmsCategoryRecord ? sprintf(CmsRouterConfig::API_METHOD_CONTENT, $cmsCategoryRecord->getScope(), $cmsCategoryRecord->getUri()) : '';
        }
        $this->_links = [
            (new LinkData())
                ->setMethod(LinkData::METHOD_REDIRECT)
                ->setRel($redirectType)
                ->setHref($href)
        ];
        return $this;
    }
}
