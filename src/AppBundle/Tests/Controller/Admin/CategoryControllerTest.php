<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 18.03.16
 * Time: 18:30
 */

namespace AppBundle\Tests\Controller\Admin;


use AppBundle\Tests\TestBaseWeb;

class CategoryControllerTest extends TestBaseWeb
{
    public function testCreateCategory()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));
        $client->request('GET', '/admin/category/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testEditCategory()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));
        $client->request('GET', '/admin/category/edit/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testRemoveCategory()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));
        $client->request('DELETE', '/admin/category/remove/1');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testShowCategory()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));
        $client->request('GET', '/admin/category');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
