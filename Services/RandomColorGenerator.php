<?php

namespace Trinity\Component\Utils\Services;

/**
 * Class RandomColorGenerator
 */
class RandomColorGenerator
{
    /**
     * @return string
     */
    public static function randomColorPart(): string
    {
        return str_pad(dechex(random_int(0, 255)), 2, '0', STR_PAD_LEFT);
    }

    /**
     * @return string
     */
    public static function randomNonRedColorPart(): string
    {
        return str_pad(dechex(random_int(0, 150)), 2, '0', STR_PAD_LEFT);
    }

    /**
     * RandomColorGenerator constructor.
     */
    private function __construct()
    {
    }

    /**
     * @return string
     */
    public static function randomColor(): string
    {
        return '#' . self::randomNonRedColorPart() . self::randomColorPart() . self::randomColorPart();
    }
}
