<?php

namespace  Trinity\Component\Utils\Tests\Entity;

/**
 * Class EntityObject
 */
class EntityObject extends EntityParent
{

    /** @var string */
    private $name;


    /** @var int */
    private $price;


    /** @var string */
    private $desc;


    /** @var bool */
    private $active;


    /**
     * EntityObject constructor.
     */
    public function __construct()
    {
        parent::__construct(11);
        $this->name   = 'Joe Dee';
        $this->price  = 10;
        $this->desc   = 'Long text...';
        $this->active = true;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }


    /**
     * @param int $price
     */
    public function setPrice(int $price)
    {
        $this->price = $price;
    }


    /**
     * @return string
     */
    public function getDesc(): string
    {
        return $this->desc;
    }


    /**
     * @param string $desc
     */
    public function setDesc(string $desc)
    {
        $this->desc = $desc;
    }


    /**
     * @return boolean
     */
    public function isActive(): bool
    {
        return $this->active;
    }


    /**
     * @param boolean $active
     */
    public function setActive(bool $active)
    {
        $this->active = $active;
    }

    /**
     * @param int $a
     * @param int $c
     *
     * @return int
     */
    public function test(int $a, int $c)
    {
        return $a + $c;
    }
}
