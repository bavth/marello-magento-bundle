<?php

namespace Marello\Bundle\PricingBundle\Twig;

use Marello\Bundle\PricingBundle\Provider\ProductChannelPriceProvider;

class PricingExtension extends \Twig_Extension
{
    const NAME = 'marello_pricing';
    
    /** @var ProductChannelPriceProvider */
    protected $provider;

    /**
     * PricingExtension constructor.
     *
     * @param ProductChannelPriceProvider $provider
     */
    public function __construct(ProductChannelPriceProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'marello_pricing_get_currency_data',
                [$this, 'getCurrencyData']
            ),
        ];
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function getCurrencyData(array $data)
    {
        if (!empty($data['currencyCode']) && !empty($data['currencySymbol'])) {
            return $this->formatData($data['currencyCode'], $data['currencySymbol']);
        }

        if(!empty($data['salesChannel'])) {
            $currencyData = $this->provider->getCurrency($data['salesChannel']);
            $key = sprintf('currency-%s', $data['salesChannel']);
            return $this->formatData($currencyData[$key]['currencyCode'], $currencyData[$key]['currencySymbol']);
        }
    }

    /**
     * Format currency data
     * @param $currencyCode
     * @param $currencySymbol
     * @return string
     */
    private function formatData($currencyCode, $currencySymbol)
    {
        return sprintf('%s (%s)', $currencyCode, $currencySymbol);
    }
}
