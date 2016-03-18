<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 18.03.16
 * Time: 17:32
 */

namespace AppBundle\Tests\Controller\Account;


use AppBundle\Tests\TestBaseWeb;

class AccountControllerTest extends TestBaseWeb
{
    public function testShowAccount()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'user@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));
        $crawler = $client->request('GET', '/account');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
      //  $this->assertContains('Add', $crawler->filter('h4')->text());
    }
}
