<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 18.03.16
 * Time: 18:33
 */

namespace AppBundle\Tests\Controller\Admin;


use AppBundle\Tests\TestBaseWeb;

class InfoPassModulesControllerTest extends TestBaseWeb
{
    public function testInfoPassModules()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@test.com',
            'PHP_AUTH_PW'   => 'user',
        ));
        $client->request('GET', '/admin/infoPassModules');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
