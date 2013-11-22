<?php

namespace Kollarovic\Thumbnail\DI;

use Nette\Config\CompilerExtension;


if (class_exists('Nette\DI\CompilerExtension')) {
	class_alias('Nette\DI\CompilerExtension', 'Nette\Config\CompilerExtension');
}


/**
 * Extension
 *
 * @author  Mario Kollarovic
 */
class Extension extends CompilerExtension
{

	public $defaults = array(
		'wwwDir' => '%wwwDir%',
		'httpRequest' => '@httpRequest',
		'thumbPathMask' => 'images/thumbs/{filename}-{width}x{height}.{extension}',
		'placeholder' => 'http://dummyimage.com/{width}x{height}/efefef/f00&text=Image+not+found'
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
	}

}

