<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 18.03.16
 * Time: 18:37
 */

namespace AppBundle\Tests\Controller\Admin;


use AppBundle\Tests\TestBaseWeb;

class ModuleUserControllerTest extends TestBaseWeb
{
    public function testCreateModuleUser()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));
        $crawler = $client->request('GET', '/admin/moduleUser/new/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testRemoveModuleUser()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));
        $crawler = $client->request('GET', '/admin/moduleUser/remove/2/1');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
