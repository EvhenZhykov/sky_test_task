<?php

namespace App\ApiBundle\Model;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class UniqueStar
{
    /**
     * @OA\Property(type="string", maxLength=255)
     */
    public $name;

    /**
     * @OA\Property(type="integer")
     */
    public $radius;

    /**
     * @OA\Property(type="integer")
     */
    public $temperature;

    /**
     * @OA\Property(type="number")
     */
    public $volume;

}