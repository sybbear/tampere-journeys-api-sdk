<?php

namespace Vikingmaster\TampereJourneysApiSdk\Dto;

class Line extends BaseDto
{
    public function getUrl()
    {
        return $this->getAttribute('url');
    }

    public function getName()
    {
        return $this->getAttribute('name');
    }

    public function getDescription()
    {
        return $this->getAttribute('description');
    }
}
