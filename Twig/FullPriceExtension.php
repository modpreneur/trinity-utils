<?php
/*
 * This file is part of the Trinity project.
 */

namespace  Trinity\Component\Utils\Twig;

use Trinity\Component\Core\Interfaces\BillingPlanInterface;
use Trinity\Component\Utils\Services\PriceStringGenerator;

/**
 * Class FullPriceExtension
 * @package Trinity\FrameworkBundle\Twig
 */
class FullPriceExtension extends \Twig_Extension
{
    /** @var PriceStringGenerator */
    protected $generator;


    /**
     * @param PriceStringGenerator $generator
     */
    public function __construct(PriceStringGenerator $generator)
    {
        $this->generator = $generator;
    }


    /**
     * @return array
     */
    public function getFunctions() : array
    {
        return [
            new \Twig_SimpleFunction('fullPrice', [$this, 'fullPrice']),
            new \Twig_SimpleFunction('fullPriceByPlan', [$this, 'fullPriceByPlan']),
            new \Twig_SimpleFunction('fullPaymentByPlans', [$this, 'fullPaymentByPlans']),
        ];
    }

    /**
     * @param array $plans
     *
     * @return string
     * @throws \UnexpectedValueException
     * @throws \Trinity\Bundle\SettingsBundle\Exception\PropertyNotExistsException
     * @throws \Symfony\Component\Intl\Exception\MethodArgumentValueNotImplementedException
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Intl\Exception\MethodArgumentNotImplementedException
     */
    public function fullPaymentByPlans(array $plans): string
    {
        return $this->generator->generatePaymentStr($plans);
    }

    /**
     * @param BillingPlanInterface $plan
     *
     * @return string
     * @throws \UnexpectedValueException
     * @throws \Symfony\Component\Intl\Exception\MethodArgumentNotImplementedException
     * @throws \Symfony\Component\Intl\Exception\MethodArgumentValueNotImplementedException
     * @throws \Trinity\Bundle\SettingsBundle\Exception\PropertyNotExistsException
     *
     */
    public function fullPriceByPlan(BillingPlanInterface $plan) :string
    {
        return $this->generator->generateFullPriceStr($plan);
    }

    /**
     * @param float $initialPrice
     * @param string $type
     * @param float $rebillPrice
     * @param int $rebillTimes
     *
     * @return string
     * @throws \UnexpectedValueException
     *
     * @throws \Trinity\Bundle\SettingsBundle\Exception\PropertyNotExistsException
     * @throws \Symfony\Component\Intl\Exception\MethodArgumentValueNotImplementedException
     * @throws \Symfony\Component\Intl\Exception\MethodArgumentNotImplementedException
     */
    public function fullPrice(float $initialPrice, string $type, float $rebillPrice, int $rebillTimes):string
    {
        return $this->generator->generateFullPrice($initialPrice, $type, $rebillPrice, $rebillTimes);
    }


    /**
     * @return string
     */
    public function getName():string
    {
        return 'trinity_admin_full_price_extension';
    }
}
