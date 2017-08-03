<?php

namespace Trinity\Component\Utils\Tests\Entity;

/**
 * Class EntityParent
 * @package Trinity\Component\Utils\Tests
 */
class EntityParent
{

    /** @var int */
    protected $id;


    /**
     * EntityParent constructor.
     *
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id     = $id;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function parentFunction(): string
    {
        return 'parent';
    }


    /**
     * @param int $a
     * @param int $b
     *
     * @return int mixed
     */
    public function add(int $a, int $b): int
    {
        return $a + $b;
    }
}
