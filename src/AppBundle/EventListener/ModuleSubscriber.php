<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 24.02.16
 * Time: 11:21
 */

namespace AppBundle\EventListener;


use AppBundle\Entity\Module;
use AppBundle\Services\ImageManagerServices;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class ModuleSubscriber implements EventSubscriber
{
    /**
     * @var ImageManagerServices
     */
    protected $service;

    protected $cache;


    /**
     * @param ImageManagerServices $container
     */
    public function __construct(ImageManagerServices $service, CacheManager $cacheManager)
    {
        $this->service = $service;
        $this->cache = $cacheManager;

    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
            'postRemove'
        );
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $module = $args->getEntity();


        if ($module instanceof Module) {
            if (null === $module->getModuleImage()) {
                return;
            }
            if (file_exists($module->getPathImage())) {
                unlink($module->getPathImage());
                $this->cache->remove($module->getPathImage());
            }

            $this->service->upload($module);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $module = $args->getEntity();

        if ($module instanceof Module) {
            if (null !== $module->getModuleImage()) {
                $this->service->upload($module);
            }


        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        $module = $args->getEntity();

        if ($module instanceof Module && $module->getPathImage() !== null && file_exists($module->getPathImage())) {
            unlink($module->getPathImage());
            $this->cache->remove($module->getPathImage());
        }

    }

}