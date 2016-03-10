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
            new \Twig_SimpleFunction('showRating',
                array($this, 'getRating')
            ),
            new \Twig_SimpleFunction('showPersent',
                array($this, 'getPersent')
            ),
            new \Twig_SimpleFunction('showTime',
                array($this, 'getTime')
            )
        );
    }

    /**
     * @return string
     */
    public function getRating(PassModule $passModule)
    {
        $rating = $passModule->getAbsoluteResult();

        return $rating;
    }

    /**
     * @return string
     */
    public function getPersent(PassModule $passModule)
    {
        $percent = $passModule->getPercentResult();

        return $percent;
    }

    /**
     * @return string
     */
    public function getTime(PassModule $passModule)
    {
        $diff = date_diff($passModule->getTimeFinish(), $passModule->getTimeStart(), true);

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
