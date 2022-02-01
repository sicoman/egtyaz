<?php
namespace Xe\Xecd\Client\Rates;

use GuzzleHttp\Client as Client;
use GuzzleHttp\ClientInterface as ClientInterface;
use GuzzleHttp\HandlerStack as HandlerStack;
use GuzzleHttp\Psr7\Request as Request;
use GuzzleHttp\RequestOptions as RequestOptions;
use Psr\Http\Message\RequestInterface as RequestInterface;
use Xe\Framework\Client\BaseClient\AbstractClient as AbstractClient;
use Xe\Xecd\Client\Rates\Annotations\Deserializable as Deserializable;
use Xe\Xecd\Client\Rates\Annotations\Serializable as Serializable;
use Xe\Xecd\Component\Rates\Core\Entity\Account as Account;
use Xe\Xecd\Component\Rates\Core\Entity\Currencies as Currencies;
use Xe\Xecd\Component\Rates\Core\Entity\Currency as Currency;
use Xe\Xecd\Component\Rates\Core\Entity\Interval as Interval;
use Xe\Xecd\Component\Rates\Core\Entity\ManyToOneConversions as ManyToOneConversions;
use Xe\Xecd\Component\Rates\Core\Entity\MonthlyAverageConversions as MonthlyAverageConversions;
use Xe\Xecd\Component\Rates\Core\Entity\OneToManyConversions as OneToManyConversions;

class XecdRatesClient extends XecdRatesClient__AopProxied implements \Go\Aop\Proxy
{

    /**
     * Property was created automatically, do not change it manually
     */
    private static $__joinPoints = [
        'method' => [
            'accountInfo' => [
                'advisor.Xe\\Framework\\Client\\BaseClient\\Aspects\\DeserializableAspect->aroundDeserializable'
            ],
            'currencies' => [
                'advisor.Xe\\Framework\\Client\\BaseClient\\Aspects\\DeserializableAspect->aroundDeserializable'
            ],
            'convertFrom' => [
                'advisor.Xe\\Framework\\Client\\BaseClient\\Aspects\\DeserializableAspect->aroundDeserializable'
            ],
            'convertTo' => [
                'advisor.Xe\\Framework\\Client\\BaseClient\\Aspects\\DeserializableAspect->aroundDeserializable'
            ],
            'historicRate' => [
                'advisor.Xe\\Framework\\Client\\BaseClient\\Aspects\\DeserializableAspect->aroundDeserializable'
            ],
            'historicRatePeriod' => [
                'advisor.Xe\\Framework\\Client\\BaseClient\\Aspects\\DeserializableAspect->aroundDeserializable'
            ],
            'monthlyAverage' => [
                'advisor.Xe\\Framework\\Client\\BaseClient\\Aspects\\DeserializableAspect->aroundDeserializable'
            ],
            'send' => [
                'advisor.Xe\\Framework\\Client\\BaseClient\\Aspects\\SerializableAspect->aroundSerializable'
            ]
        ]
    ];
    
    /**
     * Request account info associated with your api key and secret.
     *
     * @param array $options Guzzle request options
     *
     * @return \Xe\Framework\Client\BaseClient\Psr7\DeserializedResponse
     *
     * @Deserializable(type=Account::class)
     */
    public function accountInfo(array $options = array (
    ))
    {
        return self::$__joinPoints['method:accountInfo']->__invoke($this, \array_slice([$options], 0, \func_num_args()));
    }
    
    /**
     * Request currency information.
     *
     * @param string $language ISO 639-1 language code specifying the language to request currency information in
     * @param Currencies $iso  ISO 4217 currency codes to request currency information for
     * @param bool   $obsolete true to request obsolete currencies, false otherwise
     * @param array  $options  Guzzle request options
     *
     * @return \Xe\Framework\Client\BaseClient\Psr7\DeserializedResponse
     *
     * @Deserializable(type=Currencies::class)
     */
    public function currencies($language = 'en', ?\Xe\Xecd\Component\Rates\Core\Entity\Currencies $iso = NULL, $obsolete = false, array $options = array (
    ))
    {
        return self::$__joinPoints['method:currencies']->__invoke($this, \array_slice([$language, $iso, $obsolete, $options], 0, \func_num_args()));
    }
    
    /**
     * Convert from a single currency to multiple currencies.
     *
     * @param Currency   $from ISO 4217 currency code to convert from
     * @param Currencies $to   ISO 4217 currency codes to convert to
     * @param int$amount   Amount to convert
     * @param bool   $obsolete true to request rates for obsolete currencies, false otherwise
     * @param bool   $inverse  true to request inverse rates as well, false otherwise
     * @param array  $options  Guzzle request options
     *
     * @return \Xe\Framework\Client\BaseClient\Psr7\DeserializedResponse
     *
     * @Deserializable(type=OneToManyConversions::class)
     */
    public function convertFrom(?\Xe\Xecd\Component\Rates\Core\Entity\Currency $from = NULL, ?\Xe\Xecd\Component\Rates\Core\Entity\Currencies $to = NULL, $amount = 1, $obsolete = false, $inverse = false, array $options = array (
    ))
    {
        return self::$__joinPoints['method:convertFrom']->__invoke($this, \array_slice([$from, $to, $amount, $obsolete, $inverse, $options], 0, \func_num_args()));
    }
    
    /**
     * Convert to a single currency from multiple currencies.
     *
     * @param Currency   $to   ISO 4217 currency code to convert to
     * @param Currencies $from ISO 4217 currency codes to convert from
     * @param int$amount   Amount to convert
     * @param bool   $obsolete true to request rates for obsolete currencies, false otherwise
     * @param bool   $inverse  true to request inverse rates as well, false otherwise
     * @param array  $options  Guzzle request options
     *
     * @return \Xe\Framework\Client\BaseClient\Psr7\DeserializedResponse
     *
     * @Deserializable(type=ManyToOneConversions::class)
     */
    public function convertTo(?\Xe\Xecd\Component\Rates\Core\Entity\Currency $to = NULL, ?\Xe\Xecd\Component\Rates\Core\Entity\Currencies $from = NULL, $amount = 1, $obsolete = false, $inverse = false, array $options = array (
    ))
    {
        return self::$__joinPoints['method:convertTo']->__invoke($this, \array_slice([$to, $from, $amount, $obsolete, $inverse, $options], 0, \func_num_args()));
    }
    
    /**
     * Request historic rates from a single currency to multiple currencies for a specific date and time.
     *
     * @param \DateTime  $dateTime Date and time to request rates for. The time portion is only applicable to LIVE packages and for the last 24 hours
     * @param Currency   $from ISO 4217 currency code to convert from
     * @param Currencies $to   ISO 4217 currency codes to convert to
     * @param int$amount   Amount to convert
     * @param bool   $obsolete true to request rates for obsolete currencies, false otherwise
     * @param bool   $inverse  true to request inverse rates as well, false otherwise
     * @param array  $options  Guzzle request options
     *
     * @return \Xe\Framework\Client\BaseClient\Psr7\DeserializedResponse
     *
     * @Deserializable(type=OneToManyConversions::class)
     */
    public function historicRate(\DateTime $dateTime, ?\Xe\Xecd\Component\Rates\Core\Entity\Currency $from = NULL, ?\Xe\Xecd\Component\Rates\Core\Entity\Currencies $to = NULL, $amount = 1, $obsolete = false, $inverse = false, array $options = array (
    ))
    {
        return self::$__joinPoints['method:historicRate']->__invoke($this, \array_slice([$dateTime, $from, $to, $amount, $obsolete, $inverse, $options], 0, \func_num_args()));
    }
    
    /**
     * Request historic rates from a single currency to multiple currencies for a time period.
     *
     * @param \DateTime|null $startDateTime Date and time to start requesting rates for. The time portion is only applicable to LIVE packages. Defaults to the current date and time
     * @param \DateTime|null $endDateTime   Date and time to end requesting rates for. The time portion is only applicable to LIVE packages. Defaults to the current date and time
     * @param Currency   $from  ISO 4217 currency code to convert from
     * @param Currencies $toISO 4217 currency codes to convert to
     * @param int$amountAmount to convert
     * @param string $interval  Either "daily" or "live". Only applicable to LIVE packages
     * @param bool   $obsolete  true to request rates for obsolete currencies, false otherwise
     * @param bool   $inverse   true to request inverse rates as well, false otherwise
     * @param int$page  Page number of results to return
     * @param int$perPage   Number of results per page. The maximum results per page is 100
     * @param array  $options   Guzzle request options
     *
     * @return \Xe\Framework\Client\BaseClient\Psr7\DeserializedResponse
     *
     * @Deserializable(type=OneToManyConversions::class)
     */
    public function historicRatePeriod(?\DateTime $startDateTime = NULL, ?\DateTime $endDateTime = NULL, ?\Xe\Xecd\Component\Rates\Core\Entity\Currency $from = NULL, ?\Xe\Xecd\Component\Rates\Core\Entity\Currencies $to = NULL, $amount = 1, $interval = 'daily', $obsolete = false, $inverse = false, $page = 1, $perPage = 30, array $options = array (
    ))
    {
        return self::$__joinPoints['method:historicRatePeriod']->__invoke($this, \array_slice([$startDateTime, $endDateTime, $from, $to, $amount, $interval, $obsolete, $inverse, $page, $perPage, $options], 0, \func_num_args()));
    }
    
    /**
     * Request monthly averages from a single currency to multiple currencies.
     *
     * @param int|null   $year Year to request averages for. Defaults to the current year
     * @param int|null   $monthMonth to request averages for. Defaults to all months
     * @param Currency   $from ISO 4217 currency code to convert from
     * @param Currencies $to   ISO 4217 currency codes to convert to
     * @param int$amount   Amount to convert
     * @param bool   $obsolete true to request rates for obsolete currencies, false otherwise
     * @param bool   $inverse  true to request inverse rates as well, false otherwise
     * @param array  $options  Guzzle request options
     *
     * @return \Xe\Framework\Client\BaseClient\Psr7\DeserializedResponse
     *
     * @Deserializable(type=MonthlyAverageConversions::class)
     */
    public function monthlyAverage($year = NULL, $month = NULL, ?\Xe\Xecd\Component\Rates\Core\Entity\Currency $from = NULL, ?\Xe\Xecd\Component\Rates\Core\Entity\Currencies $to = NULL, $amount = 1, $obsolete = false, $inverse = false, array $options = array (
    ))
    {
        return self::$__joinPoints['method:monthlyAverage']->__invoke($this, \array_slice([$year, $month, $from, $to, $amount, $obsolete, $inverse, $options], 0, \func_num_args()));
    }
    
    /**
     * {@inheritdoc}
     *
     * @Serializable
     */
    protected function send(\Psr\Http\Message\RequestInterface $request, array $options = array (
    ))
    {
        return self::$__joinPoints['method:send']->__invoke($this, \array_slice([$request, $options], 0, \func_num_args()));
    }
    
}
\Go\Proxy\ClassProxy::injectJoinPoints(XecdRatesClient::class);