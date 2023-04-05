<?php

namespace App\ApiBundle\Serializer;

use App\SkyBundle\Entity\Star;
use App\ApiBundle\Model\UniqueStar as APIUniqueStar;
use App\ApiBundle\Model\Star as APIStar;

class Normalizer
{
    public function normalize(Star $star, ?string $tag = 'basic')
    {
        switch ($tag) {
            case 'basic':
                $volume = (4/3) * pi() * ($star->getRadius() ** 3);

                $apiStar = new APIUniqueStar;
                $apiStar->name = $star->getName();
                $apiStar->radius = $star->getRadius();
                $apiStar->temperature = $star->getTemperature();
                $apiStar->volume = $volume;

                return json_encode($apiStar);
            case 'custom':
                $apiStar = new APIStar;
                $apiStar->id = $star->getId();
                $apiStar->name = $star->getName();
                $apiStar->galaxy = $star->getGalaxy();
                $apiStar->radius = $star->getRadius();
                $apiStar->temperature = $star->getTemperature();
                $apiStar->rotationFrequency = $star->getRotationFrequency();
                $apiStar->atomsFound = [];
                foreach ($star->getAtoms()->toArray() as $atom) {
                    $apiStar->atomsFound[] = $atom->getValue();
                }
                return json_encode($apiStar);
        }
    }
}