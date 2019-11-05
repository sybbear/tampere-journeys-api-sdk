<?php

namespace Vikingmaster\TampereJourneysApiSdk\Dto;

class ApiError extends BaseDto
{
    /**
     * @return string|null
     */
    public function getMessage()
    {
        return $this->getAttribute('message');
    }

    /**
     * @return string|null
     */
    public function getTitle()
    {
        return $this->getAttribute('title');
    }
}
