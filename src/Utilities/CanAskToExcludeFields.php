<?php

namespace Vikingmaster\TampereJourneysApiSdk\Utilities;

use Vikingmaster\TampereJourneysApiSdk\Messages\Requests\BaseRequest;

/**
 * @mixin BaseRequest
 */
trait CanAskToExcludeFields
{
    /**
     * Exclude certain fields
     * @param array $fields
     * @return static
     */
    public function excludeFields(array $fields)
    {
        $value = $fields ? implode(',', $fields) : null;
        return $this->setQueryParam('exclude-fields', $value);
    }
}
