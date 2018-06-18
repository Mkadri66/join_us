<?php

namespace PartieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use UtilisateurBundle\Entity\Utilisateur;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Partie
 *
 * @ORM\Table(name="partie")
 * @ORM\Entity(repositoryClass="PartieBundle\Repository\PartieRepository")
 */
class Partie
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
    * @ORM\ManyToOne(targetEntity="UtilisateurBundle\Entity\Utilisateur")
    * @ORM\JoinColumn(nullable=false)
    */
    private $organisateur;


    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     * @Assert\NotBlank(message="Vous devez indiquer une adresse.")
     */
    private $adresse;

    /**
     * @var \Date
     *
     * @ORM\Column(name="date", type="date")
     * @Assert\NotBlank(message="Ce champ est obligatoire.")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="horaire", type="time")
     * @Assert\NotBlank(message="Ce champ est obligatoire.")
     */
    private $horaire;



    /**
     * @var bool
     *
     * @ORM\Column(name="termine", type="boolean")
     */
    private $termine;

    /**
     * @var int
     *
     * @ORM\Column(name="joueurs_actif", type="integer")
     * 
     * @Assert\Range(
     *      min = 1,
     *      max = 22,
     *      minMessage = "Vous devez avoir au minimum : {{ limit }} joueur pour créer une partie.",
     *      maxMessage = "Vous pouvez inscrir au maximum : {{ limit }} joueurs."
     * )
     */
    
    private $joueursActif;

    /**
     * @var string
     *
     * @ORM\Column(name="total_joueurs",type="integer")
     */
    private $totalJoueurs;


    /**
    * @ORM\ManyToOne(targetEntity="SportBundle\Entity\Sport")
    * @ORM\JoinColumn(nullable=false)
    */
    private $sport;

    /**
    * @ORM\ManyToOne(targetEntity="VilleBundle\Entity\Ville")
    * @ORM\JoinColumn(nullable=false)
    */
    private $ville;

    /**
    * @ORM\ManyToMany(targetEntity="UtilisateurBundle\Entity\Utilisateur", mappedBy="parties")
    * @ORM\JoinColumn(nullable=false)
    */
    private $utilisateurs; 



    public function __construct()
    {

        $this->utilisateurs = new ArrayCollection();
         
   
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
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Partie
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Partie
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set termine
     *
     * @param boolean $termine
     *
     * @return Partie
     */
    public function setTermine($termine)
    {
        $this->termine = $termine;

        return $this;
    }

    /**
     * Get termine
     *
     * @return bool
     */
    public function getTermine()
    {
        return $this->termine;
    }

    /**
     * Set joueursActif
     *
     * @param integer $joueursActif
     *
     * @return Partie
     */
    public function setJoueursActif($joueursActif)
    {
        $this->joueursActif = $joueursActif;

        return $this;
    }

    /**
     * Get joueursActif
     *
     * @return int
     */
    public function getJoueursActif()
    {
        return $this->joueursActif;
    }

    /**
     * Set totalJoueurs
     *
     * @param string $totalJoueurs
     *
     * @return Partie
     */
    public function setTotalJoueurs($totalJoueurs)
    {
        $this->totalJoueurs = $totalJoueurs;

        return $this;
    }

    /**
     * Get totalJoueurs
     *
     * @return string
     */
    public function getTotalJoueurs()
    {
        return $this->totalJoueurs;
    }

    /**
     * Add utilisateur
     *
     * @param \Utilisateur\Entity\Utilisateur $utilisateur
     *
     * @return Partie
     */
    public function addUtilisateur(\UtilisateurBundle\Entity\Utilisateur $utilisateur)
    {
        $this->utilisateurs[] = $utilisateur;

        $utilisateur->addParty($this);


        return $this;
    }

    /**
     * Remove utilisateur
     *
     * @param \Utilisateur\Entity\Utilisateur $utilisateur
     */
    public function removeUtilisateur(\UtilisateurBundle\Entity\Utilisateur $utilisateur)
    {
        $this->utilisateurs->removeElement($utilisateur);

        $utilisateur->removeParty($this);
    }

    /**
     * Set organisateur
     *
     * @param \UtilsateurBundle\Entity\Utilisateur $organisateur
     *
     * @return Partie
     */
    public function setOrganisateur(\UtilisateurBundle\Entity\Utilisateur $organisateur)
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    /**
     * Get organisateur
     *
     * @return \UtilisateurBundle\Entity\Utilisateur
     */
    public function getOrganisateur()
    {
        return $this->organisateur;
    }

    /**
     * Set sport
     *
     * @param \SportBundle\Entity\Sport $sport
     *
     * @return Partie
     */
    public function setSport(\SportBundle\Entity\Sport $sport)
    {
        $this->sport = $sport;

        return $this;
    }

    /**
     * Get sport
     *
     * @return \SportBundle\Entity\Sport
     */
    public function getSport()
    {
        return $this->sport;
    }

    /**
     * Set ville
     *
     * @param \VilleBundle\Entity\Ville $ville
     *
     * @return Partie
     */
    public function setVille(\VilleBundle\Entity\Ville $ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return \VilleBundle\Entity\Ville
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Get utilisateurs.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUtilisateurs()
    {
        return $this->utilisateurs;
    }

    /**
     * Set horaire.
     *
     * @param \DateTime $horaire
     *
     * @return Partie
     */
    public function setHoraire($horaire)
    {
        $this->horaire = $horaire;

        return $this;
    }

    /**
     * Get horaire.
     *
     * @return \DateTime
     */
    public function getHoraire()
    {
        return $this->horaire;
    }
}
