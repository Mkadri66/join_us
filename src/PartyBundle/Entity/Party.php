<?php

namespace PartyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Party
 *
 * @ORM\Table(name="party")
 * @ORM\Entity(repositoryClass="PartyBundle\Repository\PartyRepository")
 */
class Party
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var \Date
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="schedule", type="time")
     * @Assert\NotBlank(message="Ce champ est obligatoire.")
     */
    private $schedule;

    /**
     * @var bool
     *
     * @ORM\Column(name="isFinished", type="boolean")
     */
    private $isFinished;

    /**
     * @var int
     *
     * @ORM\Column(name="totalPlayers", type="integer")
     */
    private $totalPlayers;

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User" )
    * 
    */
    private $organiser;

    /**
    * @ORM\ManyToOne(targetEntity="SportBundle\Entity\Sport")
    * @ORM\JoinColumn(nullable=false)
    */
    private $sport;

    /**
    * @ORM\ManyToOne(targetEntity="CityBundle\Entity\City")
    * @ORM\JoinColumn(nullable=false)
    */
    private $city;

    /**
    * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="parties")
    * @ORM\JoinColumn(nullable=false)
    */
    private $players; 


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set address.
     *
     * @param string $address
     *
     * @return Party
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Party
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set isFinished.
     *
     * @param bool $isFinished
     *
     * @return Party
     */
    public function setIsFinished($isFinished)
    {
        $this->isFinished = $isFinished;

        return $this;
    }

    /**
     * Get isFinished.
     *
     * @return bool
     */
    public function getIsFinished()
    {
        return $this->isFinished;
    }

    /**
     * Set totalPlayers.
     *
     * @param int $totalPlayers
     *
     * @return Party
     */
    public function setTotalPlayers($totalPlayers)
    {
        $this->totalPlayers = $totalPlayers;

        return $this;
    }

    /**
     * Get totalPlayers.
     *
     * @return int
     */
    public function getTotalPlayers()
    {
        return $this->totalPlayers;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->players = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set sport.
     *
     * @param \SportBundle\Entity\Sport $sport
     *
     * @return Party
     */
    public function setSport(\SportBundle\Entity\Sport $sport)
    {
        $this->sport = $sport;

        return $this;
    }

    /**
     * Get sport.
     *
     * @return \SportBundle\Entity\Sport
     */
    public function getSport()
    {
        return $this->sport;
    }

    /**
     * Get the value of city
     */ 
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the value of city
     *
     * @return  self
     */ 
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }
    /**
     * Add player.
     *
     * @param \AppBundle\Entity\User $player
     *
     * @return Party
     */
    public function addPlayer(\AppBundle\Entity\User $player)
    {
        $this->players[] = $player;

        $player->addParty($this);
        
        return $this;
    }

    /**
     * Remove player.
     *
     * @param \AppBundle\Entity\User $player
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePlayer(\AppBundle\Entity\User $player)
    {
        $this->players->removeElement($player);


        $player->removeParty($this);
    }

    /**
     * Get players.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlayers()
    {
        return $this->players;
    }



    /**
     * Set organiser.
     *
     * @param \AppBundle\Entity\User $organiser
     *
     * @return Party
     */
    public function setOrganiser(\AppBundle\Entity\User $organiser)
    {
        $this->organiser = $organiser;

        return $this;
    }

    /**
     * Get organiser.
     *
     * @return \AppBundle\Entity\User
     */
    public function getOrganiser()
    {
        return $this->organiser;
    }

    /**
     * Set schedule.
     *
     * @param \DateTime $schedule
     *
     * @return Party
     */
    public function setSchedule($schedule)
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * Get schedule.
     *
     * @return \DateTime
     */
    public function getSchedule()
    {
        return $this->schedule;
    }
}
