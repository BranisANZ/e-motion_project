<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 */
class Location
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="locations")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\announce", mappedBy="location")
     */
    private $announce;

    public function __construct()
    {
        $this->announce = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|announce[]
     */
    public function getAnnounce(): Collection
    {
        return $this->announce;
    }

    public function addAnnounce(announce $announce): self
    {
        if (!$this->announce->contains($announce)) {
            $this->announce[] = $announce;
            $announce->setLocation($this);
        }

        return $this;
    }

    public function removeAnnounce(announce $announce): self
    {
        if ($this->announce->contains($announce)) {
            $this->announce->removeElement($announce);
            // set the owning side to null (unless already changed)
            if ($announce->getLocation() === $this) {
                $announce->setLocation(null);
            }
        }

        return $this;
    }
}
