<?php
namespace Travel\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * An example entity that represents a role.
 *
 * @ORM\Entity
 * @ORM\Table(name="travel_txt")
 */
class TravelTxt
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="travel_id")
     * @ORM\Column(type="integer")
     */
    protected $travel_id;

    /**
     * @ORM\Id
     * @ORM\Column(name="lang_id")
     * @ORM\Column(type="integer")
     */
    protected $lang_id;

    /**
     * @ORM\Column(name="title")
     */
    protected $title;

    /**
     * @ORM\Column(name="subtitle")
     */
    protected $subtitle;

    /**
     * @ORM\Column(name="announce")
     */
    protected $announce;

    /**
     * @ORM\Column(name="text")
     */
    protected $text;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMAny(targetEntity="Travel\Entity\TravelLang")
     * @ORM\JoinTable(name="lang",
     *      joinColumns={@ORM\JoinColumn(name="lang_id", referencedColumnName="lang_id")}
     * )
     */
    protected $langs;

    /**
     * Initialies the travel txt variable.
     */
    public function __construct()
    {
        $this->langs = new ArrayCollection();
    }

    /**
     * Get the role id.
     *
     * @return string
     */
    public function getTravelTxtId()
    {
        return $this->travel_id;
    }

    /**
     * Set the travel txt id.
     *
     * @param string $travel_id
     *
     * @return void
     */
    public function setTravelTxtId($travel_id)
    {
        $this->travel_id = (int) $travel_id;
    }

    /**
     * Get the lang.
     *
     * @return string
     */
    public function getLangId()
    {
        return $this->lang_id;
    }

    /**
     * Set the lang.
     *
     * @param string $lang
     *
     * @return void
     */
    public function setLangId($lang_id)
    {
        $this->lang_id = (string)$lang_id;
    }

    /**
     * Get the title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the title.
     *
     * @param string $title
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;
    }

    /**
     * Get the subtitle.
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set the subtitle.
     *
     * @param string $subtitle
     *
     * @return void
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = (string) $subtitle;
    }

    /**
     * Get the announce.
     *
     * @return string
     */
    public function getAnnounce()
    {
        return $this->announce;
    }

    /**
     * Set the announce.
     *
     * @param string $announce
     *
     * @return void
     */
    public function setAnnounce($announce)
    {
        $this->announce = (string) $announce;
    }

    /**
     * Get the text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the text.
     *
     * @param string $text
     *
     * @return void
     */
    public function setText($text)
    {
        $this->text = (string) $text;
    }

    /**
     * Helper function.
     */
    public function exchangeArray($data)
    {
        foreach ($data as $key => $val) {
            if (property_exists($this, $key)) {
                $this->$key = ($val !== null) ? $val : null;
            }
        }
    }

    /**
     * Helper function
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}