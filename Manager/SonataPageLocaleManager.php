<?php 
namespace Coolshop\CoolSonataTranslationBundle\Manager;


use Sonata\PageBundle\Entity\SiteManager;

/**
* SonataPageLocaleManager
*/
class SonataPageLocaleManager implements LocaleManagerInterface
{
	private $siteManager;


	public function __construct(SiteManager $siteManager)
	{
		$this->siteManager = $siteManager;
	}


	public function getAvailableLocales()
	{
		$locales = array_map(function($locale){
		    return $locale->getLocale();
	    }, $this->siteManager->findBy(array(
	    	'enabled' => true
	    )));
		
		return $locales;
	}
}