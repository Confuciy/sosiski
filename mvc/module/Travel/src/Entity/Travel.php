<?php

namespace Travel\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Travel
 *
 * @ORM\Table(name="travel")
 * @ORM\Entity
 */
class Travel
{
    /**
     * @var integer
     *
     * @ORM\Column(name="travel_id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $travel_id;

    /**
     * @var string
     *
     * @ORM\Column(name="user_id", type="string", precision=0, scale=0, nullable=false, unique=false)
     */
    private $user_id;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", precision=0, scale=0, nullable=false, unique=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", precision=0, scale=0, nullable=false, unique=false)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", precision=0, scale=0, nullable=false, unique=false)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", precision=0, scale=0, nullable=false, unique=false)
     */
    private $status;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Travel\Entity\TravelTxt")
     * @ORM\JoinTable(name="travel_txt",
     *   joinColumns={
     *     @ORM\JoinColumn(name="travel_id, lang_id", referencedColumnName="travel_id", nullable=true)
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="lang_id", referencedColumnName="lang_id", nullable=true)
     *   }
     * )
     */
    private $travel_txt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->travel_txt = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get travelId
     *
     * @return integer
     */
    public function getTravelId()
    {
        return $this->travel_id;
    }

    /**
     * Set userId
     *
     * @param string $userId
     *
     * @return Travel
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Travel
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Travel
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set date
     *
     * @param string $date
     *
     * @return Travel
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Travel
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add travelTxt
     *
     * @param \Travel\Entity\TravelTxt $travelTxt
     *
     * @return Travel
     */
    public function addTravelTxt(\Travel\Entity\TravelTxt $travelTxt)
    {
        $this->travel_txt[] = $travelTxt;

        return $this;
    }

    /**
     * Remove travelTxt
     *
     * @param \Travel\Entity\TravelTxt $travelTxt
     */
    public function removeTravelTxt(\Travel\Entity\TravelTxt $travelTxt)
    {
        $this->travel_txt->removeElement($travelTxt);
    }

    /**
     * Get travelTxt
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTravelTxt()
    {
        return $this->travel_txt;
    }
}

