<?php

use Trinity\Component\Utils\Tests\Entity\EntityObject;
use Trinity\Component\Utils\Utils\ObjectMixin;

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
     * @expectedException \Trinity\Component\Utils\Exception\MemberAccessException
     * @throws \Trinity\Component\Utils\Exception\MemberAccessException
     */
    public function testFunctionWithoutParams()
    {
        $e = new EntityObject();
        self::assertEquals(10, ObjectMixin::get($e, 'add()'));
    }


    /**
     * @expectedException \Trinity\Component\Utils\Exception\MemberAccessException
     * @expectedExceptionMessage Cannot read an undeclared property Trinity\Component\Utils\Tests\Entity\EntityObject::$names or method Trinity\Component\Utils\Tests\Entity\EntityObject::names(), did you mean name?
     * @throws \Trinity\Component\Utils\Exception\MemberAccessException
     */
    public function testHing()
    {
        $e = new EntityObject();
        self::assertEquals(10, ObjectMixin::get($e, 'names'));
    }


    /**
     * @expectedException Trinity\Component\Utils\Exception\MemberAccessException
     * @expectedExceptionMessage Cannot read an undeclared property Trinity\Component\Utils\Tests\Entity\EntityObject::$jhvjjv or method Trinity\Component\Utils\Tests\Entity\EntityObject::jhvjjv()
     * @throws  Trinity\Component\Utils\Exception\MemberAccessException
     */
    public function testHing2()
    {
        $e = new EntityObject();
        self::assertEquals(10, ObjectMixin::get($e, 'jhvjjv'));
    }
}
