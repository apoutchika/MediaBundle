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

use Apoutchika\MediaBundle\Services\Filter;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Apoutchika\MediaBundle\Tests\Base;

class FilterTest extends Base
{
    public function setUp()
    {
        $session = new Session(new MockArraySessionStorage());
        $this->testClass = new Filter($session);
        parent::setup();
    }

    /**
     * @dataProvider getFilterProvider
     */
    public function testFilter($name)
    {
        $key = $this->testClass->set($name);
        $this->assertEquals($name, $this->testClass->get($key));
    }

    public function getFilterProvider()
    {
        return array(
            array(null),
            array('user_1'),
            array('user_2'),
            array('user_3'),
        );
    }
}
