<?php
namespace AppBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Alice\DataFixtureLoader;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class FixturesLoader
 * @package AppBundle\DataFixtures\ORM
 */
class FixturesLoader extends DataFixtureLoader
{
    /**
     * Returns an array of file paths to fixtures
     *
     * @return array<string>
     */
    protected function getFixtures()
    {
        $env = $this->container->get('kernel')->getEnvironment();
        if ($env == 'test') {
            return [
                __DIR__ . '/DataForTests/test.yml',
            ];
        }
        return [
            __DIR__ . '/Data/categories.yml',
            __DIR__ . '/Data/users.yml',
            __DIR__ . '/Data/modules.yml',
            __DIR__ . '/Data/questions.yml',
            __DIR__ . '/Data/answers.yml',
        ];
    }


    public function encodePassword(UserInterface $user, $plainPassword)
    {
        $pass = $this->container->get('security.password_encoder')
                ->encodePassword($user, $plainPassword);
        return $pass;
    }


}