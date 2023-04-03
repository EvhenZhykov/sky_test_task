<?php

namespace App\SkyBundle\Entity;

use App\SkyBundle\Repository\StarRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass=StarRepository::class)
 * @ORM\Table(name="star",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="unique_star", columns={"name", "galaxy"})})
 */
class Star
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $galaxy;

    /**
     * @ORM\Column(type="integer")
     */
    private $radius;

    /**
     * @ORM\Column(type="integer")
     */
    private $temperature;

    /**
     * @ORM\Column(type="float")
     */
    private $rotationFrequency;

    /**
     * @ORM\Column(type="array")
     */
    private $atomsFound;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Star
     */
    public function setName(string $name): Star
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getGalaxy(): string
    {
        return $this->galaxy;
    }

    /**
     * @param string $galaxy
     * @return Star
     */
    public function setGalaxy(string $galaxy): Star
    {
        $this->galaxy = $galaxy;
        return $this;
    }

    /**
     * @return int
     */
    public function getRadius(): int
    {
        return $this->radius;
    }

    /**
     * @param int $radius
     * @return Star
     */
    public function setRadius(int $radius): Star
    {
        $this->radius = $radius;
        return $this;
    }

    /**
     * @return int
     */
    public function getTemperature(): int
    {
        return $this->temperature;
    }

    /**
     * @param mixed $temperature
     * @return Star
     */
    public function setTemperature(int $temperature): Star
    {
        $this->temperature = $temperature;
        return $this;
    }

    /**
     * @return float
     */
    public function getRotationFrequency(): float
    {
        return $this->rotationFrequency;
    }

    /**
     * @param float $rotationFrequency
     * @return Star
     */
    public function setRotationFrequency(float $rotationFrequency): Star
    {
        $this->rotationFrequency = $rotationFrequency;
        return $this;
    }

    /**
     * @return array
     */
    public function getAtomsFound()
    {
        return $this->atomsFound;
    }

    /**
     * @param array $atomsFound
     * @return Star
     */
    public function setAtomsFound(array $atomsFound): Star
    {
        $this->atomsFound = $atomsFound;
        return $this;
    }

}
