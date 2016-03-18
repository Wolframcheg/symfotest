<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 18.03.16
 * Time: 18:34
 */

namespace AppBundle\Tests\Controller\Admin;


use AppBundle\Tests\TestBaseWeb;

class ModuleControllerTest extends TestBaseWeb
{
    public function testCreateModule()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));
        $crawler = $client->request('GET', '/admin/module/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testEditModule()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));
        $crawler = $client->request('GET', '/admin/module/edit/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testRemoveModule()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));
        $crawler = $client->request('DELETE', '/admin/module/remove/1');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testShowModule()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));
        $crawler = $client->request('GET', '/admin/module');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}

