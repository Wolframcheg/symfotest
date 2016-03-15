<?php

namespace AppBundle\Twig\Extension;

use AppBundle\Entity\PassModule;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class AppExtension
 * @package AppBundle\Twig\Extension
 */
class AppExtension extends \Twig_Extension
{
    /**
     * @var RegistryInterface
     */
    protected $doctrine;

    /**
     * @param RegistryInterface $doctrine
     */
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('showTime',
                array($this, 'getTime')
            )
        );
    }

    /**
     * @return string
     */
    public function getTime($start, $finish)
    {
        if ($finish === null) {

            return 'The time is up';
        }

        $diff = date_diff($finish, $start, true);

        return $diff->format('%I:%S');
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app.twig.extension';
    }
}
