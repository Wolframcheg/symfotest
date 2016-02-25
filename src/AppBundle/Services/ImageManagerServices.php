<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 29.01.16
 * Time: 17:07
 */

namespace AppBundle\Services;


use AppBundle\Entity\Module;


/**
 * Class ImageManagerServices
 * @package AppBundle\Service
 */
class ImageManagerServices
{

    /**
     * @return string
     */
    protected function getUploadRootDir()
    {
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }

    /**
     * @return string
     */
    protected function getUploadDir()
    {
        return 'uploads/module';
    }

    /**
     * @param Module $module
     */
    public function upload(Module $module)
    {
        $randPrefix = mt_rand(1, 9999);
        $module->getModuleImage()->move(
            $this->getUploadRootDir(),
            $randPrefix . '-' . $module->getModuleImage()->getClientOriginalName()
        );
        $module->setPathImage($this->getUploadDir() . '/' . $randPrefix . '-' . $module->getModuleImage()->getClientOriginalName());
        $module->setNameImage($randPrefix . '-' . $module->getModuleImage()->getClientOriginalName());
        $module->setModuleImage(null);

        return;
    }
}