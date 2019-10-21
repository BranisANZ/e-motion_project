<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="locations")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Announce", inversedBy="locations")
     * @JoinColumn(name="announce_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $announce;


    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $returned = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $returned_at;


    public function __construct()
    {

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAnnounce(): ?announce
    {
        return $this->announce;
    }

    public function setAnnounce(?announce $announce): self
    {
        $this->announce = $announce;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReturned()
    {
        return $this->returned;
    }

    /**
     * @param mixed $returned
     * @return Location
     */
    public function setReturned($returned): self
    {
        $this->returned = $returned;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReturnedAt()
    {
        return $this->returned_at;
    }

    /**
     * @param mixed $returned_at
     * @return Location
     */
    public function setReturnedAt($returned_at): self
    {
        $this->returned_at = $returned_at;

        return $this;
    }


    public function __toString() {
        return $this->startDate->format("d/m/Y") ." - ". $this->endDate->format("d/m/Y");
    }
}
