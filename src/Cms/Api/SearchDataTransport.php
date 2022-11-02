<?php

namespace Cms\Api;

/**
 * Template data object
 */
class SearchDataTransport extends HttpJsonTransport
{
    public int $total = 0;
    public int $offset = 0;
    public int $limit = 0;
    public array $filterBy = [];
    public array $sortBy = [];
    public array $list = [];

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function setOffset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    public function setFilterBy(array $filterBy): self
    {
        $this->filterBy = $filterBy;

        return $this;
    }

    public function setSortBy(array $sortBy): self
    {
        $this->sortBy = $sortBy;

        return $this;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function setList(array $list): self
    {
        $this->list = $list;

        return $this;
    }
}
