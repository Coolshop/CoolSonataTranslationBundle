<?php

namespace Coolshop\CoolSonataTranslationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CoolTranslationLocale
 *
 * @ORM\Table(name="cool_translation_locale")
 * @ORM\Entity(repositoryClass="Coolshop\CoolSonataTranslationBundle\Repository\CoolTranslationLocaleRepository")
 * @ORM\HasLifecycleCallbacks
 */
class CoolTranslationLocale
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
     * @var CoolTranslationKey
     * 
     * @ORM\ManyToOne(
     *     targetEntity="CoolTranslationKey",
     *     inversedBy="translations"
     * )
     * @ORM\JoinColumn(
     *     name="trans_key_id",
     *     referencedColumnName="id",
     *     nullable=false
     * )
     */
    private $transKey;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=5)
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="text")
     */
    private $label;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreated", type="datetime")
     */
    protected $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateUpdated", type="datetime")
     */
    protected $dateUpdated;


    public function __construct(CoolTranslationKey $transKey = null, $locale = null, $label = null)
    {
        $this->transKey = $transKey;
        $this->locale = $locale;
        $this->label = $label;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreated()
    {
        $this->dateCreated = new \DateTime();
        $this->dateUpdated = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdated()
    {
        $this->dateUpdated = new \DateTime();
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
     * Set locale
     *
     * @param string $locale
     *
     * @return CoolTranslationLocale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return CoolTranslationLocale
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set label
     *
     * @param CoolTranslationKey $transKey
     *
     * @return CoolTranslationLocale
     */
    public function setTransKey($transKey)
    {
        $this->transKey = $transKey;

        return $this;
    }

    /**
     * Get transKey
     *
     * @return CoolTranslationKey
     */
    public function getTransKey()
    {
        return $this->transKey;
    }

    /**
     * Set dateCreated
     *
     * @param string $dateCreated
     *
     * @return CoolTranslationKey
     */
    public function setDateCreated(\DateTime $dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return string
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateUpdated
     *
     * @param string $dateUpdated
     *
     * @return CoolTranslationKey
     */
    public function setDateUpdated(\DateTime $dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * Get dateUpdated
     *
     * @return string
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

}

