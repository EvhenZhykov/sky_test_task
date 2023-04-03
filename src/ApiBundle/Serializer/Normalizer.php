<?php

namespace App\ApiBundle\Serializer;

use App\SkyBundle\Entity\Star;

class Normalizer
{
    public function normalize(Star $star, $tag)
    {
        $volume = (4/3) * pi() * ($star->getRadius() ** 3);
        $data = [
            'name' => $star->getName(),
            'radius' => $star->getRadius(),
            'temperature' => $star->getTemperature(),
            'volume' => $volume
        ];

        switch ($tag) {
            case 'basic':
                return json_encode($data);
            case 'custom':
                // add functionality for custom normalize
                break;
        }
    }
}