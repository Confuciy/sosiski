<?php

namespace Travel\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TravelLang
 *
 * @ORM\Table(name="lang")
 * @ORM\Entity
 */
class TravelLang
{
    /**
     * @var string
     *
     * @ORM\Column(name="lang_id", type="string", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $lang_id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=10, precision=0, scale=0, nullable=true, unique=true)
     */
    private $name;


    /**
     * Get langId
     *
     * @return string
     */
    public function getLangId()
    {
        return $this->lang_id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return TravelLang
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}

