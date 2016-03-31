<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 20.03.16
 * Time: 10:40
 */

namespace AppBundle\Services;


class RandomGenerator
{
    public function generator()
    {
        $key = '';
        $array = array_merge(range('A','Z'),range('a','z'),range('0','9'));
        $c = count($array)-1;
        for($i = 0; $i < 6; $i++) {
            $key .= $array[rand(0, $c)];
        }

        return $key;
    }
}