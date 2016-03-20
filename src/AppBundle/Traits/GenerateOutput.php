<?php
namespace AppBundle\Traits;

trait GenerateOutput{

    private function generateOutput($status, $code, $message)
    {
        return [
            'status' => $status,
            'code' => $code,
            'content' => $message
        ];
    }

}