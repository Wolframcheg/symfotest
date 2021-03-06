<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 18.03.16
 * Time: 17:54
 */

namespace AppBundle\Tests\Controller;


use AppBundle\Tests\TestBaseWeb;

class PassingTestControllerTest extends TestBaseWeb
{
    public function testIdentPass()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'user@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));

        $client->request('GET', '/account/ident-pass/1');
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testPass()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'user@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));

        $client->request('GET', '/account/pass-module/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPassResult()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'user@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));

        $client->request('GET', '/account/pass-result/1');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}
