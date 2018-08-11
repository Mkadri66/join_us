<?php

namespace UtilisateurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity(repositoryClass="UtilisateurBundle\Repository\UtilisateurRepository")
 * @UniqueEntity(
 *  fields = {"mail"},
 *  message = "L'email que vous avez entré est deja utilisé !"
 * )
 */
class Utilisateur implements UserInterface, \Serializable,EquatableInterface
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
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255)
     * @Assert\Email(
     *     message = "L'email '{{ value }}' n'est pas au bon format ."
     * )
     */
    
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\NotBlank()
     * 
     */
    private $password;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
    * @ORM\Column(name="salt", type="string", length=255)
    */

    private $salt;

    /**
    * @ORM\ManyToOne(targetEntity="VilleBundle\Entity\Ville")
    * @ORM\JoinColumn(nullable=false)
    * @Assert\Valid()
    */
    private $ville;


     /**
     * @ORM\OneToOne(targetEntity="ImageBundle\Entity\Image", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $avatar;


    /**
    */
    private $roles = array();


    /**
    * @ORM\ManyToMany(targetEntity="PartieBundle\Entity\Partie", inversedBy="utilisateurs")
    * @ORM\JoinColumn(nullable=false)
    * @ORM\JoinTable(name="partie_utilisateur")
    * @Assert\Valid()
    */
    private $parties;


    public function __construct()
    {

        $this->parties = new ArrayCollection();
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
        $this->roles = array('ROLE_USER', 'ROLE_ADMIN');

    }



    public function getParties()
    {
        return $this->parties;
    }



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        // allows for chaining
        return $this;
    }
    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Utilisateur
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Utilisateur
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Utilisateur
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Utilisateur
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set mdp
     *
     * @param string $mdp
     *
     * @return Utilisateur
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get mdp
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return Utilisateur
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }





    /**
     * Set avatar
     *
     * @param \ImageBundle\Entity\Image $avatar
     *
     * @return Utilisateur
     */
    public function setAvatar(\ImageBundle\Entity\Image $avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \ImageBundle\Entity\Image
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Add party
     *
     * @param \PartieBundle\Entity\Partie $party
     *
     * @return Utilisateur
     */
    public function addParty(\PartieBundle\Entity\Partie $party)
    {
        $this->parties[] = $party;

        return $this;
    }

    /**
     * Remove party
     *
     * @param \PartieBundle\Entity\Partie $party
     */
    public function removeParty(\PartieBundle\Entity\Partie $party)
    {
        $this->parties->removeElement($party);
    }

    /**
     * Add avatar
     *
     * @param \ImageBundle\Entity\Image $avatar
     *
     * @return Utilisateur
     */
    public function addAvatar(\ImageBundle\Entity\Image $avatar)
    {
        $this->avatar[] = $avatar;

        return $this;
    }

    /**
     * Remove avatar
     *
     * @param \ImageBundle\Entity\Image $avatar
     */
    public function removeAvatar(\ImageBundle\Entity\Image $avatar)
    {
        $this->avatar->removeElement($avatar);
    }

    /**
     * Set isActive.
     *
     * @param bool $isActive
     *
     * @return Utilisateur
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive.
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    public function isEqualTo(UserInterface $user)
    {
        return $this->id === $user->getId();
    }
}
