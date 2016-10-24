<?php

namespace Coolshop\CoolSonataTranslationBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Coolshop\CoolSonataTranslationBundle\DependencyInjection\Compiler\AddResourcePass;
use Coolshop\CoolSonataTranslationBundle\DependencyInjection\Compiler\RegisterFileLoadersPass;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CoolSonataTranslationBundle extends Bundle
{
    
	/**
	* @param ContainerBuilder $container
	*/
	public function build(ContainerBuilder $container) {
		parent::build($container);

		$container->addCompilerPass(new AddResourcePass());
        $container->addCompilerPass(new RegisterFileLoadersPass());
	}

}
