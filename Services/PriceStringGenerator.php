<?php

namespace Trinity\Component\Utils\Services;

use Symfony\Component\Intl\NumberFormatter\NumberFormatter;
use Trinity\Bundle\SettingsBundle\Manager\SettingsManager;
use Trinity\Component\Core\Interfaces\BillingPlanInterface;

/**
 * Class PriceStringGenerator
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
     *
     * @return string
     * @throws \UnexpectedValueException
     * @throws \Trinity\Bundle\SettingsBundle\Exception\PropertyNotExistsException
     * @throws \Symfony\Component\Intl\Exception\MethodArgumentValueNotImplementedException
     * @throws \Symfony\Component\Intl\Exception\MethodArgumentNotImplementedException
     */
    public function generateFullPriceStr(BillingPlanInterface $billingPlan): string
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
     * @param BillingPlanInterface[] $billingPlans
     *
     * @return string
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Intl\Exception\MethodArgumentNotImplementedException
     * @throws \Symfony\Component\Intl\Exception\MethodArgumentValueNotImplementedException
     * @throws \Trinity\Bundle\SettingsBundle\Exception\PropertyNotExistsException
     */
    public function generatePaymentStr(array $billingPlans):string
    {
        $hasToBeSame = ['rebillTimes', 'trial', 'frequency'];

        $rebillPrice = 0;
        $rebillTimes = 0;
        $initialPrice = 0;
        $trial = 0;
        $frequency = 0;

        foreach ($billingPlans as $plan) {
            $initialPrice += $plan->getInitialPrice();
            if ($plan->getType() === 'standard') {
                continue;
            }
            $rebillPrice += $plan->getRebillPrice();

            foreach ($hasToBeSame as $attribute) {
                $getter = 'get' . \ucfirst($attribute);
                if ($$attribute && $$attribute !== $plan->$getter()) {
                    throw new \InvalidArgumentException(
                        "Billing plans with different $attribute can not be combinated."
                    );
                }
                $$attribute = $plan->$getter();
            }
        }

        return $this->generatePaymentString(
            $initialPrice,
            $rebillTimes ? 'recurring' : 'standard',
            $rebillPrice,
            $rebillTimes,
            $frequency,
            $trial
        );
    }


    /**
     * @param BillingPlanInterface $billingPlan
     *
     * @return string
     * @throws \UnexpectedValueException
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
     * @param float $initialPrice
     * @param string $type
     * @param float|null $rebillPrice
     * @param int|null $rebillTimes
     * @param int|null $frequency
     *
     * @return string
     * @throws \UnexpectedValueException
     * @throws \Symfony\Component\Intl\Exception\MethodArgumentValueNotImplementedException
     * @throws \Symfony\Component\Intl\Exception\MethodArgumentNotImplementedException
     * @throws \Trinity\Bundle\SettingsBundle\Exception\PropertyNotExistsException
     */
    public function generateFullPrice(
        float $initialPrice,
        string $type = 'standard',
        ?float $rebillPrice = 0,
        ?int $rebillTimes = 0,
        ?int $frequency = 0
    ):string {
        $currency = $this->settingsManager->get('currency');

        $formatter = new NumberFormatter($this->locale, NumberFormatter::CURRENCY);

        if ($type === 'standard') {
            return $formatter->formatCurrency($initialPrice, $currency);
        }

        $str = $this->frequencyString($frequency);
        if ($rebillTimes === 999) {
            return $formatter->formatCurrency($initialPrice + 0, $currency) . ' and '
            . $formatter->formatCurrency($rebillPrice + 0, $currency) . ' ' . $str;
        }

        return $formatter->formatCurrency($initialPrice + 0, $currency) . ' and '
        . $rebillTimes . ' times ' . $formatter->formatCurrency($rebillPrice + 0, $currency) . ' ' . $str;
    }

    /**
     * @param float $initialPrice
     * @param string $type
     * @param float|null $rebillPrice
     * @param int|null $rebillTimes
     * @param int|null $frequency
     * @param int|null $trial
     *
     * @return string
     * @throws \UnexpectedValueException
     * @throws \Trinity\Bundle\SettingsBundle\Exception\PropertyNotExistsException
     * @throws \Symfony\Component\Intl\Exception\MethodArgumentNotImplementedException
     * @throws \Symfony\Component\Intl\Exception\MethodArgumentValueNotImplementedException
     *
     */
    public function generatePaymentString(
        float $initialPrice,
        string $type = 'standard',
        ?float $rebillPrice = 0,
        ?int $rebillTimes = 0,
        ?int $frequency = 0,
        ?int $trial = 0
    ): string {
        $currency = $this->settingsManager->get('currency');

        $formatter = new NumberFormatter($this->locale, NumberFormatter::CURRENCY);

        if ($type === 'standard') {
            return $formatter->formatCurrency($initialPrice, $currency);
        }

        $str = '';

        if ($initialPrice !== $rebillPrice) {
            $str .= $formatter->formatCurrency($initialPrice, $currency) . ' first payment, then ';
        }

        if ($rebillTimes && $rebillTimes !== 999) {
            $str .= $rebillTimes . ' payment';
            $str .= ($rebillTimes > 1) ? 's' : '';
            $str .= ' of ';
        }

        $str .= $formatter->formatCurrency($rebillPrice, $currency);
        $str .= ' ' . $this->frequencyString($frequency);
        if ($trial) {
            $str .= ' with ' . $trial . ' trial day';
            $str .= $trial > 1 ? 's' : '';
        }
        return $str;
    }


    /**
     * @param float $initialPrice
     * @param string $type
     * @param float|null $rebillPrice
     * @param int|null $rebillTimes
     * @param int|null $frequency
     *
     * @return string
     * @throws \UnexpectedValueException
     * @throws \Trinity\Bundle\SettingsBundle\Exception\PropertyNotExistsException
     * @throws \Symfony\Component\Intl\Exception\MethodArgumentNotImplementedException
     * @throws \Symfony\Component\Intl\Exception\MethodArgumentValueNotImplementedException
     *
     */
    public function generateShortPaymentString(
        float $initialPrice,
        string $type = 'standard',
        ?float $rebillPrice = 0,
        ?int $rebillTimes = 0,
        ?int $frequency = 0
    ): string {
        $currency = $this->settingsManager->get('currency');

        $formatter = new NumberFormatter($this->locale, NumberFormatter::CURRENCY);

        if ($type === 'standard') {
            return $formatter->formatCurrency($initialPrice, $currency);
        }

        $str = '';

        if ($initialPrice !== $rebillPrice) {
            $str .= $formatter->formatCurrency($initialPrice, $currency) . ', plus ';
        }

        $str .= $formatter->formatCurrency($rebillPrice, $currency);

        if ($rebillTimes && $rebillTimes !== 999) {
            $str .= ' x ' . $rebillTimes;
        } else {
            $str .= ' ' . $this->frequencyString($frequency);
        }

        return $str;
    }


    /**
     * @param int $frequency
     * @return string
     */
    private function frequencyString(int $frequency) :string
    {
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
        return $str;
    }
}
