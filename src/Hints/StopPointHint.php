<?php

namespace Vikingmaster\TampereJourneysApiSdk\Hints;

class StopPointHint
{
    /** @var string */
    public $url;
    /** @var string */
    public $location;
    /** @var string */
    public $name;
    /** @var string */
    public $shortName;
    /** @var string */
    public $tariffZone;
    /** @var MunicipalityHint */
    public $municipality;
}
