<?php

namespace Kollarovic\Thumbnail\DI;

use Nette;


if (!class_exists('Nette\DI\CompilerExtension')) {
	class_alias('Nette\Config\CompilerExtension', 'Nette\DI\CompilerExtension');
}


/**
 * Extension
 *
 * @author  Mario Kollarovic
 */
class Extension extends Nette\DI\CompilerExtension
{

	public $defaults = array(
		'wwwDir' => '%wwwDir%',
		'httpRequest' => '@httpRequest',
		'thumbPathMask' => 'images/thumbs/{filename}-{width}x{height}.{extension}',
		'placeholder' => 'http://dummyimage.com/{width}x{height}/efefef/f00&text=Image+not+found',
		'filterName' => 'thumbnail',
	);


	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('thumbnail'))
			->setClass('Kollarovic\Thumbnail\Generator', array(
				'wwwDir' => $config['wwwDir'],
				'httpRequest' => $config['httpRequest'],
				'thumbPathMask' => $config['thumbPathMask'],
				'placeholder' => $config['placeholder']
			));

		if ($builder->hasDefinition('nette.latteFactory')) {
			$definition = $builder->getDefinition('nette.latteFactory');
			$definition->addSetup('addFilter', array($config['filterName'], array($this->prefix('@thumbnail'), 'thumbnail')));
		}
	}

}
