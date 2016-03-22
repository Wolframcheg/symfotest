<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 18.03.16
 * Time: 17:58
 */

namespace AppBundle\Tests\Controller;


use AppBundle\Tests\TestBaseWeb;

class RegistrationControllerTest extends TestBaseWeb
{
    public function testRegister()
    {
        $client = static::createClient();

        $client->request('GET', '/registration');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testRegisterSocialNet()
    {
        $client = static::createClient();

        $client->request('GET', '/account/update-profile');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
