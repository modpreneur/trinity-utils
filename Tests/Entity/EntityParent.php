<?php

namespace Trinity\Bundle\UtilsBundle\Tests\Entity;

/**
 * Class EntityParent
 * @package Trinity\Bundle\UtilsBundle\Tests
 */
class EntityParent
{

    protected $id;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function parentFunction()
    {
        return 'parent';
    }

    /**
     * @param $a
     * @param $b
     *
     * @return mixed
     */
    public function add($a, $b)
    {
        return $a + $b;
    }
}
