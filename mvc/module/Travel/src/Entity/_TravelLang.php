<?php
namespace Travel\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="lang")
 */
class TravelLang
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\Column(name="lang_id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $lang_id;

    /**
     * @var string
     * @ORM\Column(type="string", length=10, unique=true, nullable=true)
     */
    protected $name;

    /**
     * Get the id.
     *
     * @return int
     */
    public function getLangId()
    {
        return $this->lang_id;
    }

    /**
     * Set the id.
     *
     * @param int lang_id
     *
     * @return void
     */
    public function setLangId($lang_id)
    {
        $this->lang_id = (int)$lang_id;
    }

    /**
     * Get the role id.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name.
     *
     * @param string $roleId
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = (string) $name;
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