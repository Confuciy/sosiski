<?php

namespace Travel\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TravelTxt
 *
 * @ORM\Table(name="travel_txt")
 * @ORM\Entity
 */
class TravelTxt
{
    /**
     * @var integer
     *
     * @ORM\Column(name="travel_id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $travel_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="lang_id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $lang_id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", precision=0, scale=0, nullable=false, unique=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="subtitle", type="string", precision=0, scale=0, nullable=false, unique=false)
     */
    private $subtitle;

    /**
     * @var string
     *
     * @ORM\Column(name="announce", type="string", precision=0, scale=0, nullable=false, unique=false)
     */
    private $announce;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", precision=0, scale=0, nullable=false, unique=false)
     */
    private $text;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Travel\Entity\TravelLang")
     * @ORM\JoinTable(name="lang",
     *   joinColumns={
     *     @ORM\JoinColumn(name="lang_id", referencedColumnName="lang_id", nullable=true)
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="travellang_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $langs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->langs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set travelId
     *
     * @param integer $travelId
     *
     * @return TravelTxt
     */
    public function setTravelId($travelId)
    {
        $this->travel_id = $travelId;

        return $this;
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
     * Set langId
     *
     * @param integer $langId
     *
     * @return TravelTxt
     */
    public function setLangId($langId)
    {
        $this->lang_id = $langId;

        return $this;
    }

    /**
     * Get langId
     *
     * @return integer
     */
    public function getLangId()
    {
        return $this->lang_id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return TravelTxt
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set subtitle
     *
     * @param string $subtitle
     *
     * @return TravelTxt
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set announce
     *
     * @param string $announce
     *
     * @return TravelTxt
     */
    public function setAnnounce($announce)
    {
        $this->announce = $announce;

        return $this;
    }

    /**
     * Get announce
     *
     * @return string
     */
    public function getAnnounce()
    {
        return $this->announce;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return TravelTxt
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Add lang
     *
     * @param \Travel\Entity\TravelLang $lang
     *
     * @return TravelTxt
     */
    public function addLang(\Travel\Entity\TravelLang $lang)
    {
        $this->langs[] = $lang;

        return $this;
    }

    /**
     * Remove lang
     *
     * @param \Travel\Entity\TravelLang $lang
     */
    public function removeLang(\Travel\Entity\TravelLang $lang)
    {
        $this->langs->removeElement($lang);
    }

    /**
     * Get langs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLangs()
    {
        return $this->langs;
    }
}

