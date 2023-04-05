<?php

namespace App\SkyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="atom")
 */
class Atom
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @ORM\ManyToMany(targetEntity="App\SkyBundle\Entity\Star", mappedBy="atoms")
     */
    private $stars;

    public function __construct()
    {
        $this->stars = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @param $value
     * @return Atom
     */
    public function setValue($value): Atom
    {
        $this->value = $value;
        return $this;
    }


    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return Collection
     */
    public function getStars(): Collection
    {
        return $this->stars;
    }

    /**
     * @param Star $star
     * @return Atom
     */
    public function addStar(Star $star): Atom
    {
        if (!$this->stars->contains($star)) {
            $this->stars[] = $star;
            $star->addAtom($this);
        }
        return $this;
    }

    /**
     * @param Star $star
     * @return Atom
     */
    public function removeStar(Star $star): Atom
    {
        if ($this->stars->contains($star)) {
            $this->stars->removeElement($star);
            $star->removeAtom($this);
        }
        return $this;
    }

}