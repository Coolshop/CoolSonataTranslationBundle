<?php

namespace Coolshop\CoolSonataTranslationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * CoolTranslationKey
 *
 * @ORM\Table(
 *     name="cool_translation_key",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="ix_translation_key", columns={"transKey", "domain"})}
 * )
 * @ORM\Entity(repositoryClass="Coolshop\CoolSonataTranslationBundle\Repository\CoolTranslationKeyRepository")
 */
class CoolTranslationKey
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
     * @ORM\Column(name="transKey", type="string", length=255)
     */
    private $transKey;

    /**
     * @var string
     *
     * @ORM\Column(name="domain", type="string", length=255)
     */
    private $domain;

    /**
     * @var array<CoolTranslationLocale>
     * 
     * @ORM\OneToMany(
     *     targetEntity="CoolTranslationLocale",
     *     mappedBy="transKey"
     * )
     */
    private $translations;

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
     * Set transKey
     *
     * @param string $transKey  
     *
     * @return CoolTranslationKey
     */
    public function setTransKey($transKey)
    {
        $this->transKey = $transKey;

        return $this;
    }

    /**
     * Get transKey
     *
     * @return string
     */
    public function getTransKey()
    {
        return $this->transKey;
    }

    /**
     * Set domain
     *
     * @param string $domain
     *
     * @return CoolTranslationKey
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Returns collection of translations.
     *
     * @return ArrayCollection
     */
    public function getTranslations()
    {
        return $this->translations = $this->translations ?: new ArrayCollection();
    }

    /**
     * Returns translation for a locale of translations.
     *
     * @return ArrayCollection
     */
    public function getTranslation($locale)
    {
        foreach ($this->translations as $translation) {
            if ($translation->getLocale() == $locale){
                return $translation;
            }
        }
        return null;
    }

    /**
     * Adds new translation.
     *
     * @param Translation $translation The translation
     *
     * @return $this
     */
    public function addTranslation($translation)
    {
        $this->getTranslations()->set((string)$translation->getLocale(), $translation);
        return $this;
    }

    /**
     * Removes specific translation.
     *
     * @param Translation $translation The translation
     */
    public function removeTranslation($translation)
    {
        $this->getTranslations()->removeElement($translation);
    }

    /**
     * checker
     * @param  [type]  $locale [description]
     * @return boolean         [description]
     */
    public function hasTranslation($locale)
    {
        foreach ($this->translations as $translation) {
            if ($translation->getLocale() == $locale){
                return true;
            }
        }
        return false;
    }

    
    public function getTranslationByLocale($locale)
    {
        foreach ($this->translations as $translation) {
            if ($translation->getLocale() == $locale){
                return $translation;
            }
        }
        return null;
    }

}

