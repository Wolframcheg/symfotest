<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 18.03.16
 * Time: 18:02
 */

namespace AppBundle\Tests\Controller\Admin;


use AppBundle\Tests\TestBaseWeb;

class AdminInfoUsersControllerTest extends TestBaseWeb
{
    public function testShowAccount()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));
        $crawler = $client->request('GET', '/admin/account/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
