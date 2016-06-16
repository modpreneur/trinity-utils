<?php

use Trinity\Bundle\UtilsBundle\Tests\Entity\EntityObject;
use Trinity\Bundle\UtilsBundle\Utils\ObjectMixin;

/**
 * Class UtilsObjectTest
 */
class UtilsObjectTest extends \PHPUnit_Framework_TestCase
{

    public function testMixins()
    {
        $e = new EntityObject();
        self::assertEquals('11', ObjectMixin::get($e, 'id'));
        self::assertEquals('parent', ObjectMixin::get($e, 'parentFunction'));
        self::assertEquals('parent', ObjectMixin::get($e, 'parentFunction()'));
        self::assertEquals('parent', ObjectMixin::get($e, 'parentFunction'));
        self::assertEquals('Joe Dee', ObjectMixin::get($e, 'name'));
        self::assertEquals(10, ObjectMixin::get($e, 'add(4, 6)'));
    }


    /**
     *
     * @expectedException \Trinity\Bundle\UtilsBundle\Exception\MemberAccessException
     * @throws \Trinity\Bundle\UtilsBundle\Exception\MemberAccessException
     */
    public function testFunctionWithoutParams()
    {
        $e = new EntityObject();
        self::assertEquals(10, ObjectMixin::get($e, 'add()'));
    }


    /**
     * @expectedException \Trinity\Bundle\UtilsBundle\Exception\MemberAccessException
     * @expectedExceptionMessage Cannot read an undeclared property Trinity\Bundle\UtilsBundle\Tests\Entity\EntityObject::$names or method Trinity\Bundle\UtilsBundle\Tests\Entity\EntityObject::names(), did you mean name?
     * @throws \Trinity\Bundle\UtilsBundle\Exception\MemberAccessException
     */
    public function testHing()
    {
        $e = new EntityObject();
        self::assertEquals(10, ObjectMixin::get($e, 'names'));
    }


    /**
     * @expectedException Trinity\Bundle\UtilsBundle\Exception\MemberAccessException
     * @expectedExceptionMessage Cannot read an undeclared property Trinity\Bundle\UtilsBundle\Tests\Entity\EntityObject::$jhvjjv or method Trinity\Bundle\UtilsBundle\Tests\Entity\EntityObject::jhvjjv()
     * @throws  Trinity\Bundle\UtilsBundle\Exception\MemberAccessException
     */
    public function testHing2()
    {
        $e = new EntityObject();
        self::assertEquals(10, ObjectMixin::get($e, 'jhvjjv'));
    }
}
