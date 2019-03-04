<?php 

namespace AppBundle\Entity;

use PartyBundle\Entity\Party;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** 
    * Avatar for user
    *
    * @var UploadedFile
    * @Assert\File(mimeTypes={"image/gif", "image/jpeg", "image/png"}) 
    */
    protected $avatar;
     
    
    /**
    * Link in database
    *
    * @var string
    * 
    * @ORM\Column(name="link", type="string", length=255) 
    */
    private $url;

    /**
    * @ORM\ManyToOne(targetEntity="CityBundle\Entity\City")
    * @ORM\JoinColumn(nullable=false)
    * @Assert\Valid()
    */
    private $city;

    /**
    * @ORM\ManyToMany(targetEntity="PartyBundle\Entity\Party", inversedBy="players")
    * @ORM\JoinColumn(nullable=false)
    * @ORM\JoinTable(name="party_player")
    * @Assert\Valid()
    */
    private $parties;

    

    public function __construct()
    {
        parent::__construct();
        $this->parties = new ArrayCollection();
    }

    public function getParent()
    {
        return 'FOSUserBundle';
    }

    /**
     * Get avatar for user
     *
     * @return  UploadedFile
     */ 
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set avatar for user
     *
     * @param  UploadedFile  $avatar  Avatar for user
     *
     * @return  self
     */ 
    public function setAvatar(UploadedFile $avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get link in database
     *
     * @return  string
     */ 
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set link in database
     *
     * @param  string  $url  Link in database
     *
     * @return  self
     */ 
    public function setUrl(string $url)
    {
        $this->url = $url;

        return $this;
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
     * Add party.
     *
     * @param \PartyBundle\Entity\Party $party
     *
     * @return User
     */
    public function addParty(\PartyBundle\Entity\Party $party)
    {
        $this->parties[] = $party;

        return $this;
    }

    /**
     * Remove party.
     *
     * @param \PartyBundle\Entity\Party $party
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeParty(\PartyBundle\Entity\Party $party)
    {
        return $this->parties->removeElement($party);
    }

    /**
     * Get parties.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParties()
    {
        return $this->parties;
    }
}
