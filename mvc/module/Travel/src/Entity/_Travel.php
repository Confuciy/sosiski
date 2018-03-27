<?php
namespace Travel\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a travel.
 * @ORM\Entity()
 * @ORM\Table(name="travel")
 */
class Travel
{
    // Travel status constants.
    const STATUS_ACTIVE       = 1; // Active travel.
    const STATUS_DISACTIVE    = 0; // Disactive travel.

    /**
     * @ORM\Id
     * @ORM\Column(name="travel_id")
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $travel_id;

    /**
     * @ORM\Column(name="user_id")
     */
    protected $user_id;

    /**
     * @ORM\Column(name="url")
     */
    protected $url;

    /**
     * @ORM\Column(name="image")
     */
    protected $image;

    /**
     * @ORM\Column(name="date")
     */
    protected $date;

    /**
     * @ORM\Column(name="status")
     */
    protected $status;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Travel\Entity\TravelTxt")
     * @ORM\JoinTable(name="travel_txt",
     *      joinColumns={@ORM\JoinColumn(name="travel_id, lang_id", referencedColumnName="travel_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="lang_id", referencedColumnName="lang_id")},
     *
     * )
     */
    protected $travel_txt;

    /**
     * Returns travel ID.
     * @return integer
     */
    public function getTravelId()
    {
        return $this->travel_id;
    }

    /**
     * Sets travel ID.
     * @param int $travel_id
     */
    public function setTravelId($travel_id)
    {
        $this->travel_id = $travel_id;
    }

    /**
     * Returns user ID.
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Sets user ID.
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Returns url.
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets url.
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Returns image.
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Sets image.
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * Returns the date.
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets the date.
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Returns status.
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Returns possible statuses as array.
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE     => 'Active',
            self::STATUS_DISACTIVE  => 'Disactive'
        ];
    }

    /**
     * Returns user status as string.
     * @return string
     */
    public function getStatusAsString()
    {
        $list = self::getStatusList();
        if (isset($list[$this->status]))
            return $list[$this->status];

        return 'Unknown';
    }

    /**
     * Sets status.
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get travel txt.
     *
     * @return array
     */
    public function getTravelTxt()
    {
        return $this->travel_txt->getValues();
    }

    /**
     * Add a travel txt to the travel.
     *
     * @param TravelTxt $travel_txt
     *
     * @return void
     */
    public function addTravelTxt($travel_txt)
    {
        $this->travel_txt[] = $travel_txt;
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
