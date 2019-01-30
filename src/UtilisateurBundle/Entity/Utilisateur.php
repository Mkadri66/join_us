<?php

namespace UtilisateurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;

use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity(repositoryClass="UtilisateurBundle\Repository\UtilisateurRepository")
 * @UniqueEntity(
 *  fields = {"username"},
 *  message = "Ce pseudo est deja utilisé !"
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
     * @Assert\NotBlank(
     *      message = "Ce champ est obligatoire"
     * )
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     * @Assert\NotBlank(
     *      message = "Ce champ est obligatoire"
     * )
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     * @Assert\NotBlank(
     *      message = "Ce champ est obligatoire"
     * )    
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
    * 
    * Avatar for user
    *
    * @var UploadedFile
    * 
    *
    * @Assert\File(mimeTypes={"image/gif", "image/jpeg", "image/png"})
    * 
    *
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
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\Length( min=4, minMessage="Votre mot de passe doit avoir au minimum 4 caractères.")
     * 
     * @Assert\NotBlank(
     *      message = "Ce champ est obligatoire"
     * )
     * 
     */
    private $password;

    /**
     * @var string
     *
     * 
     *
     * @Assert\EqualTo( propertyPath="password", message = "Vous n'avez pas tapé le même mot de passe" )
     * 
     */

    private $confirm_password;

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
    */
    private $roles = array();


    /**
    * @ORM\ManyToMany(targetEntity="PartieBundle\Entity\Partie", inversedBy="utilisateurs")
    * @ORM\JoinColumn(nullable=false)
    * @ORM\JoinTable(name="partie_utilisateur")
    * @Assert\Valid()
    */
    private $parties;

    // On ajoute cet attribut pour y stocker le nom du fichier temporairement

    private $tempFilename;


    public function __construct()
    {

        $this->parties = new ArrayCollection();
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
        $this->roles = array('ROLE_USER', 'ROLE_ADMIN');

    }

    // Getters ans setters

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
     * Set avatar
     *
     * @param UploadedFile $file
     *
     *
     */
    public function setAvatar(UploadedFile $file = null)
    {
        $this->avatar = $file;
    }
    
    /**
     * Get avatar
     * 
     * @return string
     *
     */
    public function getavatar()
    {
        return $this->avatar;
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
     * Get  $confirm_password
     *
     * @return string
     */
    public function getConfirmPassword()
    {
        return $this->confirm_password;
    }
    /**
     * Set confirm_password
     *
     * @param string $confirm_password
     *
     * @return Utilisateur
     */
    public function setConfirmPassword($confirm_password)
    {
        $this->confirm_password = $confirm_password;

        return $this;
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

    /**
     * Set salt.
     *
     * @param string $salt
     *
     * @return Utilisateur
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

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
}
