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

    public function getId()
    {
        return $this->id;
    }


    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }


    public function getValue()
    {
        return $this->value;
    }


    public function getStars(): Collection
    {
        return $this->stars;
    }

    public function addStar(Star $star): self
    {
        if (!$this->stars->contains($star)) {
            $this->stars[] = $star;
            $star->addAtom($this);
        }
        return $this;
    }
    public function removeStar(Star $star): self
    {
        if ($this->stars->contains($star)) {
            $this->stars->removeElement($star);
            $star->removeAtom($this);
        }
        return $this;
    }

}