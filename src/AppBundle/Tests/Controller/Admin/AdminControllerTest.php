<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 18.03.16
 * Time: 18:01
 */

namespace AppBundle\Tests\Controller\Admin;


use AppBundle\Tests\TestBaseWeb;

class AdminControllerTest extends TestBaseWeb
{
    public function testAdmin()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));
        $crawler = $client->request('GET', '/admin');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
