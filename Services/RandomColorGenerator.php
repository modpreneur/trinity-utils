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
    public function randomColorPart(): string
    {
        return str_pad(dechex(random_int(0, 255)), 2, '0', STR_PAD_LEFT);
    }

    /**
     * @return string
     */
    public function randomNonRedColorPart(): string
    {
        return str_pad(dechex(random_int(0, 150)), 2, '0', STR_PAD_LEFT);
    }

    /**
     * @return string
     */
    public function randomColor(): string
    {
        return '#' . $this->randomNonRedColorPart() . $this->randomColorPart() . $this->randomColorPart();
    }
}
