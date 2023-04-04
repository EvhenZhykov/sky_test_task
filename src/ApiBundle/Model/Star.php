<?php

namespace App\ApiBundle\Model;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class Star
{
    /**
     * @OA\Property(type="integer")
     */
    public $id;

    /**
     * @OA\Property(type="string", maxLength=255)
     */
    public $name;

    /**
     * @OA\Property(type="string", maxLength=255)
     */
    public $galaxy;

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
    public $rotationFrequency;

    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(
     *          type="integer"
     *     )
     * )
     */
    public $atomsFound;

}