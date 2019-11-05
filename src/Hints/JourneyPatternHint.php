<?php

namespace Vikingmaster\TampereJourneysApiSdk\Messages\Responses;

use Vikingmaster\TampereJourneysApiSdk\Hints\StopPointHint;

class JourneyPatternHint
{
    /** @var string */
    public $url;
    /** @var string */
    public $routeUrl;
    /** @var string */
    public $lineUrl;
    /** @var string */
    public $originStop;
    /** @var string */
    public $destinationStop;
    /** @var string */
    public $direction;
    /** @var string */
    public $name;

    /** @var array|StopPointHint[] */
    public $stopPoints;
}
