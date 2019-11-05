<?php

namespace Vikingmaster\TampereJourneysApiSdk\Dto;

class Paging extends BaseDto
{
    public function getStartIndex()
    {
        return $this->getAttribute('startIndex');
    }

    public function getPageSize()
    {
        return $this->getAttribute('pageSize');
    }

    public function hasMoreData()
    {
        return $this->getAttribute('moreData');
    }
}
