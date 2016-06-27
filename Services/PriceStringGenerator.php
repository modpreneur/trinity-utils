<?php

namespace Trinity\Component\Utils\TwigExtensions;

use Symfony\Component\Intl\NumberFormatter\NumberFormatter;
use Trinity\Bundle\SettingsBundle\Manager\SettingsManager;
use Trinity\Component\Core\Interfaces\BillingPlanInterface;

/**
 * Class PriceStringGenerator
 * @package Trinity\Component\TwigExtensions
 */
class PriceStringGenerator
{
    /** @var  SettingsManager */
    protected $settingsManager;

    /** @var string */
    protected $locale;


    /**
     * @param SettingsManager $settingsManager
     * @param string $locale
     */
    public function __construct(SettingsManager $settingsManager, string $locale)
    {
        $this->settingsManager = $settingsManager;
        $this->locale = $locale;
    }


    /**
     * @param BillingPlanInterface $billingPlan
     * @return string
     * @throws \Trinity\Bundle\SettingsBundle\Exception\PropertyNotExistsException
     * @throws \Symfony\Component\Intl\Exception\MethodArgumentValueNotImplementedException
     * @throws \Symfony\Component\Intl\Exception\MethodArgumentNotImplementedException
     */
    public function generateFullPriceStr(BillingPlanInterface $billingPlan) : string
    {
        return $this->generateFullPrice(
            $billingPlan->getInitialPrice(),
            $billingPlan->getType(),
            $billingPlan->getRebillPrice(),
            $billingPlan->getRebillTimes(),
            $billingPlan->getFrequency()
        );
    }


    /**
     * @param BillingPlanInterface $billingPlan
     * @return string
     * @throws \Trinity\Bundle\SettingsBundle\Exception\PropertyNotExistsException
     * @throws \Symfony\Component\Intl\Exception\MethodArgumentValueNotImplementedException
     * @throws \Symfony\Component\Intl\Exception\MethodArgumentNotImplementedException
     */
    public function genProductNameAndFullPriceStr(BillingPlanInterface $billingPlan) : string
    {
        $name = $billingPlan->getProduct() ? $billingPlan->getProduct()->getName() . ' : ' : '';
        return $name . $this->generateFullPrice(
            $billingPlan->getInitialPrice(),
            $billingPlan->getType(),
            $billingPlan->getRebillPrice(),
            $billingPlan->getRebillTimes(),
            $billingPlan->getFrequency()
        );
    }


    /**
     * @param int $initialPrice
     * @param string $type
     * @param int $rebillPrice
     * @param int $rebillTimes
     * @param int $frequency
     *
     * @return string
     *
     * @throws \Symfony\Component\Intl\Exception\MethodArgumentValueNotImplementedException
     * @throws \Symfony\Component\Intl\Exception\MethodArgumentNotImplementedException
     * @throws \Trinity\Bundle\SettingsBundle\Exception\PropertyNotExistsException
     */
    public function generateFullPrice(
        int $initialPrice,
        string $type = 'standard',
        $rebillPrice = 0,
        $rebillTimes = 0,
        $frequency = 0
    ):string
    {
        $currency = $this->settingsManager->get('currency');

        $formatter = new NumberFormatter($this->locale, NumberFormatter::CURRENCY);

        if ($type === 'standard') {
            return $formatter->formatCurrency($initialPrice, $currency);
        } else {
            switch ($frequency) {
                case 7:
                    $str = 'weekly';
                    break;
                case 14:
                    $str = 'bi-weekly';
                    break;
                case 30:
                    $str = 'monthly';
                    break;
                case 91:
                    $str = 'quartaly';
                    break;
                default:
                    $str = '';
            }
            if ($rebillTimes === 999) {
                return $formatter->formatCurrency($initialPrice + 0, $currency) . ' and '
                . $formatter->formatCurrency($rebillPrice + 0, $currency) . ' ' . $str;
            }

            return $formatter->formatCurrency($initialPrice + 0, $currency) . ' and '
            . $rebillTimes . ' times ' . $formatter->formatCurrency($rebillPrice + 0, $currency) . ' ' . $str;
        }

    }
}
