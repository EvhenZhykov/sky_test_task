<?php

namespace App\ApiBundle\Serializer;

use App\SkyBundle\Entity\Star;

class Normalizer
{
    public function normalize(Star $star, ?string $tag = 'basic')
    {
        switch ($tag) {
            case 'basic':
                $volume = (4/3) * pi() * ($star->getRadius() ** 3);
                $data = [
                    'name' => $star->getName(),
                    'radius' => $star->getRadius(),
                    'temperature' => $star->getTemperature(),
                    'volume' => $volume
                ];
                return json_encode($data);
            case 'custom':
                // add functionality for custom normalize
                break;
        }
    }
}