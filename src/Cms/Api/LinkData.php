<?php

namespace Cms\Api;

/**
 * Link data object
 */
class LinkData implements DataInterface
{
    public const METHOD_GET                 = 'GET';
    public const METHOD_PUT                 = 'PUT';
    public const METHOD_POST                = 'POST';
    public const METHOD_DELETE              = 'DELETE';
    public const METHOD_PATCH               = 'PATCH';
    public const METHOD_REDIRECT            = 'REDIRECT';

    public const REL_EXTERNAL               = 'external';
    public const REL_INTERNAL               = 'internal';
    public const REL_CONTENTS               = 'contents';
    public const REL_STRUCTURE              = 'structure';
    public const REL_SELF                   = 'self';
    public const REL_CONTENT                = 'content';
    public const REL_CONFIG                 = 'config';

    public const INTERNAL_REDIRECT_PREFIX   = 'internal://';

    public string   $href;
    public string   $rel;
    public string   $method;

    public function setHref(string $href): self
    {
        $this->href = $href;
        return $this;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    public function setRel(string $rel): self
    {
        $this->rel = $rel;
        return $this;
    }
}
