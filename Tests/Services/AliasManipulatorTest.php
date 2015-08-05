<?php

/*
 * This file is part of the ApoutchikaMediaBundle package.
 *
 * @author Julien Philippon <juphilippon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apoutchika\MediaBundle\Tests\Services;

use Apoutchika\MediaBundle\Services\AliasManipulator;
use Apoutchika\MediaBundle\Tests\Base;

class AliasManipulatorTest extends Base
{
    public function setUp()
    {
        $this->testClass = new AliasManipulator('original', array(
            'testA' => array('width' => 800, 'height' => 100, 'focus' => true),
            'testB' => array('width' => 800, 'height' => 100, 'focus' => false),
            'testC' => array('width' => 800, 'height' => null, 'focus' => false),
            'testD' => array('width' => null, 'height' => 100, 'focus' => false),
            'testE' => array('width' => null, 'height' => null, 'focus' => false),
        ));
        parent::setup();
    }

    /**
     * @dataProvider getAliasNameProvider
     */
    public function testGetAliasName($alias, $aliasName)
    {
        $this->testClass->setAlias($alias);
        $this->assertEquals($aliasName, $this->testClass->getAliasName());
    }

    public function getAliasNameProvider()
    {
        return array(
            array('123x456_focus', '123x456_focus'),
            array('123x456',       '123x456'),
            array('x456',          'x456'),
            array('x456_focus',    'x456'),
            array('123x',          '123x'),
            array('123x_focus',    '123x'),

            array('original', 'original'),

            array('testA', '800x100_focus'),
            array('testB', '800x100'),
            array('testC', '800x'),
            array('testD', 'x100'),
            array('testE', 'original'),

            array(array('width' => 123, 'height' => 456, 'focus' => true), '123x456_focus'),
            array(array('width' => 123, 'height' => 456, 'focus' => false), '123x456'),
            array(array('width' => 123, 'height' => 456), '123x456_focus'),

            array(array('width' => null, 'height' => 456, 'focus' => false), 'x456'),
            array(array('width' => null, 'height' => 456, 'focus' => true), 'x456'),
            array(array('width' => null, 'height' => 456), 'x456'),
            array(array('height' => 456), 'x456'),

            array(array('width' => 123, 'height' => null, 'focus' => false), '123x'),
            array(array('width' => 123, 'height' => null, 'focus' => true), '123x'),
            array(array('width' => 123, 'focus' => false), '123x'),
            array(array('width' => 123, 'height' => null), '123x'),
            array(array('width' => 123), '123x'),

            array(array('width' => null, 'height' => null, 'focus' => false), 'original'),
            array(array('width' => null, 'height' => null, 'focus' => true), 'original'),
            array(array('height' => null, 'focus' => false), 'original'),
            array(array('width' => null, 'focus' => false), 'original'),
            array(array('height' => null, 'focus' => true), 'original'),
            array(array('width' => null, 'focus' => true), 'original'),
            array(array(), 'original'),
        );
    }

    /**
     * @dataProvider getAliasArrayProvider
     */
    public function testGetAliasArray($alias, $aliasArray)
    {
        $this->testClass->setAlias($alias);
        $this->assertEquals($aliasArray, $this->testClass->getAliasArray());
    }

    public function getAliasArrayProvider()
    {
        return array(
            array('123x456_focus', array('width' => 123, 'height' => 456, 'focus' => true)),
            array('123x456'      , array('width' => 123, 'height' => 456, 'focus' => false)),
            array('x456', array('width' => null, 'height' => 456, 'focus' => false)),
            array('x456_focus', array('width' => null, 'height' => 456, 'focus' => false)),
            array('123x', array('width' => 123, 'height' => null, 'focus' => false)),
            array('123x_focus', array('width' => 123, 'height' => null, 'focus' => false)),

            array('testA', array('width' => 800, 'height' => 100, 'focus' => true)),
            array('testB', array('width' => 800, 'height' => 100, 'focus' => false)),
            array('testC', array('width' => 800, 'height' => null, 'focus' => false)),
            array('testD', array('width' => null, 'height' => 100, 'focus' => false)),
            array('testE', array('width' => null, 'height' => null, 'focus' => false)),

            array(array('width' => 123, 'height' => 456, 'focus' => true), array('width' => 123, 'height' => 456, 'focus' => true)),
            array(array('width' => 123, 'height' => 456, 'focus' => false), array('width' => 123, 'height' => 456, 'focus' => false)),
            array(array('width' => 123, 'height' => 456), array('width' => 123, 'height' => 456, 'focus' => true)),

            array(array('width' => null, 'height' => 456, 'focus' => false), array('width' => null, 'height' => 456, 'focus' => false)),
            array(array('width' => null, 'height' => 456, 'focus' => true), array('width' => null, 'height' => 456, 'focus' => false)),
            array(array('width' => null, 'height' => 456), array('width' => null, 'height' => 456, 'focus' => false)),
            array(array('height' => 456), array('width' => null, 'height' => 456, 'focus' => false)),

            array(array('width' => 123, 'height' => null, 'focus' => false), array('width' => 123, 'height' => null, 'focus' => false)),
            array(array('width' => 123, 'height' => null, 'focus' => true), array('width' => 123, 'height' => null, 'focus' => false)),
            array(array('width' => 123, 'focus' => false), array('width' => 123, 'height' => null, 'focus' => false)),
            array(array('width' => 123, 'height' => null), array('width' => 123, 'height' => null, 'focus' => false)),
            array(array('width' => 123), array('width' => 123, 'height' => null, 'focus' => false)),

            array(array('width' => null, 'height' => null, 'focus' => false), array('width' => null, 'height' => null, 'focus' => false)),
            array(array('width' => null, 'height' => null, 'focus' => true), array('width' => null, 'height' => null, 'focus' => false)),
            array(array('height' => null, 'focus' => false), array('width' => null, 'height' => null, 'focus' => false)),
            array(array('width' => null, 'focus' => false), array('width' => null, 'height' => null, 'focus' => false)),
            array(array('height' => null, 'focus' => true), array('width' => null, 'height' => null, 'focus' => false)),
            array(array('width' => null, 'focus' => true), array('width' => null, 'height' => null, 'focus' => false)),
            array(array(), array('width' => null, 'height' => null, 'focus' => false)),
        );
    }
}
