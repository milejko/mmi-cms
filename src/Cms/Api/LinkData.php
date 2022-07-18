<?php

namespace Cms\Api;

/**
 * Link data object
 */
class LinkData implements DataInterface
{
    const METHOD_GET        = 'GET';
    const METHOD_PUT        = 'PUT';
    const METHOD_POST       = 'POST';
    const METHOD_DELETE     = 'DELETE';
    const METHOD_PATCH      = 'PATCH';
    const METHOD_REDIRECT   = 'REDIRECT';

    const REL_EXTERNAL      = 'external';
    const REL_INTERNAL      = 'internal';
    const REL_CONTENTS      = 'contents';
    const REL_CONTENT       = 'content';
    const REL_CONFIG        = 'config';

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
