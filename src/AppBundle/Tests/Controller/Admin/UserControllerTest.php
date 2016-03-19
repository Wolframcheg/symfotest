<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 18.03.16
 * Time: 18:55
 */

namespace AppBundle\Tests\Controller\Admin;


use AppBundle\Tests\TestBaseWeb;

class UserControllerTest extends TestBaseWeb
{
    public function testIndex()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));
        $client->request('GET', '/admin/user');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testEdit()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));
        $client->request('GET', '/admin/user/2/edit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testDelete()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));
        $client->request('DELETE', '/admin/user/2');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}

