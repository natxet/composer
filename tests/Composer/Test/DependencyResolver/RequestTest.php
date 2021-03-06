<?php

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Composer\Test\DependencyResolver;

use Composer\DependencyResolver\Request;
use Composer\DependencyResolver\Pool;
use Composer\Repository\ArrayRepository;
use Composer\DependencyResolver\Literal;
use Composer\Test\TestCase;

class RequestTest extends TestCase
{
    public function testRequestInstallAndRemove()
    {
        $pool = new Pool;
        $repo = new ArrayRepository;
        $foo = $this->getPackage('foo', '1');
        $bar = $this->getPackage('bar', '1');
        $foobar = $this->getPackage('foobar', '1');

        $repo->addPackage($foo);
        $repo->addPackage($bar);
        $repo->addPackage($foobar);
        $pool->addRepository($repo);

        $request = new Request($pool);
        $request->install('foo');
        $request->install('bar');
        $request->remove('foobar');

        $this->assertEquals(
            array(
                array('packages' => array($foo), 'cmd' => 'install', 'packageName' => 'foo'),
                array('packages' => array($bar), 'cmd' => 'install', 'packageName' => 'bar'),
                array('packages' => array($foobar), 'cmd' => 'remove', 'packageName' => 'foobar'),
            ),
            $request->getJobs());
    }

    public function testUpdateAll()
    {
        $pool = new Pool;
        $request = new Request($pool);

        $request->updateAll();

        $this->assertEquals(
            array(array('cmd' => 'update-all', 'packages' => array())),
            $request->getJobs());
    }
}
