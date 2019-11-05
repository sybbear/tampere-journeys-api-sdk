<?php

namespace Vikingmaster\TampereJourneysApiSdk\Messages\Responses;

class GetJourneyPatternsResponse extends BaseResponse
{
    /**
     * @return JourneyPatternHint[]|array|\stdClass[]
     */
    public function getJourneyPatterns()
    {
        return $this->toObjects($this->getBodyElements());
    }
}
