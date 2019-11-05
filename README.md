# Tampere Journey API SDK

This is an unofficial SDK release for [Tampere Journeys API](http://wiki.itsfactory.fi/index.php/Journeys_API).

# Installation
Install using composer: `composer require vikingmaster/tampere`

# Usage
Creating an API Client instance. Available config parameters: 
* `baseUri` Base URI to make requests to
* `timeout` (optional) Http request timeout in seconds
* `userAgent` (optional) User-Agent header

```php
$api = new \Vikingmaster\TampereJourneysApiSdk\TampereJourneysApiClient([
    'baseUri' => 'http://data.itsfactory.fi/journeys/api'
]);
```

### Fetching lines:
```php
//Fetch lines
$request = $api->makeGetLinesRequest()
    ->setIndent(true)
    ->setDescription('Description')
;
$response = $request->send();
$lines = $response->getLines();
```

### Fetching journey patterns
```php
$request = $api->makeGetJourneyPatternsRequest()
    ->setFirstStopPointId(1)
    ->setLastStopPointId(2)
    ->setLineId(17)
    ->setName("Nokian asema C - Keho")
;
$response = $request->send();
$patterns = $response->getJourneyPatterns();
```


# Exception handling
When there is a request error, TampereJourneyApiException will be thrown:

```php
use \Vikingmaster\TampereJourneysApiSdk\Exceptions\TampereJourneyApiException;

try {
    $response = $request->send();
} catch (TampereJourneyApiException $e) {
    //These methods are available for problem tracing
    $apiError  = $e->getApiError();
    $request   = $e->getRequest();
    $response  = $e->getResponse();
    $apiClient = $e->getApiClient();
} catch (\Exception $e) {
    //Any other errors such as network or configuration error
}
```

# Pagination
Some of the responses can be long, so multiple requests might be needed to fetch everything

```php
/** @var array|\Vikingmaster\TampereJourneysApiSdk\Dto\Line[] $entries */
$entries = [];

$startIndex = 0;
$fetch     = true;

while ($fetch) {
    try {
        $response = $request->setStartIndex($startIndex)->send();
    } catch (\Exception $e) {
         //Handle exception / resend the request
        break;
    }

    $startIndex = $response->getPaging()->getPageSize();
    $fetch      = $response->getPaging()->hasMoreData();

    $entries = array_merge($entries, $response->getLines());

    if ($startIndex >= 100) {
        break;
    }
}
```
