<?php

declare(strict_types=1);

namespace Kollarovic\Thumbnail\DI;

use Nette\DI\Definitions\FactoryDefinition;
use Nette\DI\CompilerExtension;


/**
 * Extension
 *
 * @author  Mario Kollarovic
 */
class Extension extends CompilerExtension
{

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		$defaults = [
			'wwwDir' => $builder->parameters['wwwDir'],
			'httpRequest' => '@httpRequest',
			'thumbPathMask' => 'images/thumbs/{filename}-{width}x{height}.{extension}',
			'placeholder' => 'http://dummyimage.com/{width}x{height}/efefef/f00&text=Image+not+found',
			'filterName' => 'thumbnail',
		];

		$config = $this->validateConfig($defaults);

		$builder->addDefinition($this->prefix('thumbnail'))
			->setFactory('Kollarovic\Thumbnail\Generator', array(
				'wwwDir' => $config['wwwDir'],
				'httpRequest' => $config['httpRequest'],
				'thumbPathMask' => $config['thumbPathMask'],
				'placeholder' => $config['placeholder']
			));

		if ($builder->hasDefinition('nette.latteFactory')) {
			$definition = $builder->getDefinition('nette.latteFactory');
            if ($definition instanceof FactoryDefinition) {
                $definition->getResultDefinition()->addSetup('addFilter', array($config['filterName'], array($this->prefix('@thumbnail'), 'thumbnail')));
            }
		}
	}

}