<?php 
namespace Coolshop\CoolSonataTranslationBundle\Manager;



/**
* ConfigLocaleManager
*/
class ConfigLocaleManager implements LocaleManagerInterface
{
	private $localeAvailable;


	public function __construct(array $localeAvailable)
	{
		$this->localeAvailable = $localeAvailable;
	}


	public function getAvailableLocales()
	{
		return $this->localeAvailable;
	}
}