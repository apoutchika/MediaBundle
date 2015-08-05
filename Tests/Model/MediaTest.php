<?php

/*
 * This file is part of the ApoutchikaMediaBundle package.
 *
 * @author Julien Philippon <juphilippon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apoutchika\MediaBundle\Tests\Entity;

use Apoutchika\MediaBundle\Tests\Base;

class MediaTest extends Base
{
    public function setUp()
    {
        $this->testClass = $this->getMockForAbstractClass('Apoutchika\MediaBundle\Model\Media');
        parent::setup();
    }

    public function testConstruct()
    {
        $this->assertInstanceOf('Datetime', $this->getProperty('createdAt'));
        $this->assertInstanceOf('Datetime', $this->getProperty('updatedAt'));
        $this->assertEquals(false, $this->getProperty('focusIsEdited'));
        $this->assertEquals(null, $this->getProperty('filter'));
        $this->assertEquals(50, $this->getProperty('focusLeft'));
        $this->assertEquals(50, $this->getProperty('focusTop'));
    }

    public function testToString()
    {
        $this->assertEquals('n/a', $this->testClass);
        $this->testClass->setName('Hello Media');
        $this->assertNotEquals('n/a', $this->testClass);
        $this->assertEquals('Hello Media', $this->testClass);
    }

    /**
     * @dataProvider getSetterAndGetterProvider
     */
    public function testSetterAndGetter($name, $before, $value)
    {
        $this->assertNotEquals($before, $value);

        $get = 'get'.ucFirst($name);
        $set = 'set'.ucFirst($name);

        $this->assertEquals($before, $this->testClass->$get());
        $this->testClass->$set($value);
        $this->assertNotEquals($before, $this->testClass->$get());
        $this->assertEquals($value, $this->testClass->$get());
    }

    public function getSetterAndGetterProvider()
    {
        $testFile = $this->getMockBuilder('\Symfony\Component\HttpFoundation\File\File')
            ->setConstructorArgs(array(__DIR__.'/../Files/symfony.png'))
            ->getMock();

        return array(
            array('name', null, 'Hello World'),
            array('alt', null, 'alt'),
            array('description', null, 'description'),
            array('width', null, 123),
            array('height', null, 321),
            array('size', null, 42),
            array('file', null, $testFile),
            array('mimeType', null, 'image/png'),
            array('extension', null, 'png'),
            array('reference', null, 'ref.png'),
            array('filter', null, 'user_1'),
            array('cryptedFilter', null, sha1('user_1')),
            array('urls', null, array('url1', 'url2')),
            array('html', null, '<img src="" alt="" />'),
        );
    }

    public function testType()
    {
        $this->assertEquals(null, $this->testClass->getType());

        $this->testClass->setType($this->getConstant('OTHER'));
        $this->assertNotEquals(null, $this->testClass->getType());
        $this->assertEquals(1, $this->testClass->getType());

        $this->testClass->setType($this->getConstant('IMAGE'));
        $this->assertEquals(2, $this->testClass->getType());

        $this->testClass->setType($this->getConstant('VIDEO'));
        $this->assertEquals(3, $this->testClass->getType());

        $this->testClass->setType($this->getConstant('AUDIO'));
        $this->assertEquals(4, $this->testClass->getType());
    }

    /**
     * @dataProvider getFocusProvider
     */
    public function testFocus($left, $top, $edited)
    {
        $this->assertEquals(50, $this->testClass->getFocusLeft());
        $this->assertEquals(50, $this->testClass->getFocusTop());
        $this->assertEquals(false, $this->testClass->getFocusIsEdited());

        if ($left !== null) {
            $this->testClass->setFocusLeft($left);
        }

        if ($top !== null) {
            $this->testClass->setFocusTop($top);
        }

        $this->assertEquals($left, $this->testClass->getFocusLeft());
        $this->assertEquals($top, $this->testClass->getFocusTop());
        $this->assertEquals($edited, $this->testClass->getFocusIsEdited());
    }

    public function getFocusProvider()
    {
        return array(
            array(50, 50, false),
            array(42, 50, true),
            array(50, 42, true),
            array(42, 42, true),
        );
    }

    public function testCreatedAtAndUpdatedAt()
    {
        // Other best method for this ?
        $now = new \Datetime();
        $this->assertEquals($now, $this->testClass->getCreatedAt());
        $this->assertEquals($now, $this->testClass->getUpdatedAt());

        $tomorrow = new \Datetime('tomorrow');
        $this->assertLessThan($tomorrow, $this->testClass->getCreatedAt());
        $this->assertLessThan($tomorrow, $this->testClass->getUpdatedAt());
        $this->testClass->setUpdatedAt($tomorrow);
        $this->assertEquals($tomorrow, $this->testClass->getUpdatedAt());
    }
}
