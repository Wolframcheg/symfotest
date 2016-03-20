<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 18.03.16
 * Time: 18:52
 */

namespace AppBundle\Tests\Controller\Admin;


use AppBundle\Tests\TestBaseWeb;

class QuestionControllerTest extends TestBaseWeb
{
    public function testCreateQuestion()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));
        $client->request('GET', '/admin/question/new/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testEditQuestion()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));
        $client->request('GET', '/admin/question/edit/1/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testRemoveQuestion()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));
        $client->request('DELETE', '/admin/question/remove/1/1');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testShowQuestion()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));
       $client->request('GET', '/admin/question/show/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
