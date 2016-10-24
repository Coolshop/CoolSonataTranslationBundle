<?php
/**
 * @namespace Coolshop\CoolSonataTranslationBundle\Translation
 */
namespace Coolshop\CoolSonataTranslationBundle\Translation;

use Coolshop\CoolSonataTranslationBundle\Model\TranslationManagerInterface;
use Symfony\Component\Config\Resource\ResourceInterface;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;

/**
 * Class DatabaseLoader
 *
 * @package Coolshop\CoolSonataTranslationBundle\Service
 * @author Alberto Brino <alberto@coolshop.it>
 * @uses Symfony\Component\Translation\Loader\LoaderInterface
 * @uses Symfony\Component\Translation\MessageCatalogue
 */
class DatabaseLoader implements LoaderInterface, ResourceInterface
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $translationManager;

    /**
     * default constructor
     *
     * @param \Doctrine\ORM\EntityRepository $translationManager
     */
    public function __construct(\Doctrine\ORM\EntityRepository $translationManager)
    {
        $this->translationManager = $translationManager;
    }

    /**
     * load messages from db
     *
     * @param string $resource translation key
     * @param string $locale current locale
     * @param string $messageDomain message domain
     * @return \Symfony\Component\Translation\MessageCatalogue
     */
    public function load($resource, $locale, $messageDomain = 'messages')
    {
        // get our translations, obviously
        $translationsKeys = $this->translationManager
            ->findTranslationsByLocaleAndDomain($locale, $messageDomain);

        $catalogue = new MessageCatalogue($locale);

        foreach ($translationsKeys as $transKey) {
            foreach ($transKey as $transLabel) {
                $catalogue->set($transKey->getTransKey(), $transKey->getLabel(), $messageDomain);
            }
        }

        return $catalogue;
    }

    /**
     * Returns a string representation of the Resource.
     *
     * @return string A string representation of the Resource
     */
    public function __toString()
    {
        return 'DatabaseLoader::'.base64_encode(get_class_methods(__CLASS__));
    }

    /**
     * Returns true if the resource has not been updated since the given timestamp.
     *
     * @param int $timestamp The last time the resource was loaded
     * @return bool True if the resource has not been updated, false otherwise
     */
    public function isFresh($timestamp)
    {
        $count = $this->translationManager->findTranslationFreshness($timestamp);

        // If we cannot fetch from database, keep the cache, even if it's not fresh.
        if (false === $count) {
            return true;
        }

        return (bool) $count;
    }

    /**
     * Returns the tied resource.
     *
     * @return mixed The resource
     */
    public function getResource()
    {
        return $this;
    }
}
