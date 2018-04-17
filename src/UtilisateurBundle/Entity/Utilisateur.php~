<?php

namespace UtilisateurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity(repositoryClass="UtilisateurBundle\Repository\UtilisateurRepository")
 */
class Utilisateur
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
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudo", type="string", length=255)
     */
    private $pseudo;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="mdp", type="string", length=255)
     */
    private $mdp;

    /**
    * @ORM\ManyToOne(targetEntity="VilleBundle\Entity\Ville")
    * @ORM\JoinColumn(nullable=false)
    */
    private $ville;


     /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255)
     */
    private $avatar;


    /**
    * @ORM\ManyToOne(targetEntity="RoleBundle\Entity\Role")
    * @ORM\JoinColumn(nullable=false)
    */
    private $role;



    /**
    * @ORM\ManyToMany(targetEntity="PartieBundle\Entity\Partie", inversedBy="parties")
    * @ORM\JoinColumn(nullable=false)
    */
    private $parties;


    public function __construct()
    {

        // $this->parties = new ArrayCollection();
    }


    public function ajouterJoueur(Partie $partie)
    {
        $this->parties[] = $partie;
    }


    public function retirerJoueur(Partie $partie)
    {
        $this->parties->removeElement($partie);
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
     * Set pseudo
     *
     * @param string $pseudo
     *
     * @return Utilisateur
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get pseudo
     *
     * @return string
     */
    public function getPseudo()
    {
        return $this->pseudo;
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
    public function setMdp($mdp)
    {
        $this->mdp = $mdp;

        return $this;
    }

    /**
     * Get mdp
     *
     * @return string
     */
    public function getMdp()
    {
        return $this->mdp;
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
     * Set role
     *
     * @param \RoleBundle\Entity\Role $role
     *
     * @return Utilisateur
     */
    public function setRole(\RoleBundle\Entity\Role $role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \RoleBundle\Entity\Role
     */
    public function getRole()
    {
        return $this->role;
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
}
