<?php

namespace Kollarovic\Thumbnail;

use Nette;


/**
 * @author  Mario Kollarovic
 *
 * Generator
 */
class Generator extends AbstractGenerator
{

	/**
	 * @return void
	 */
	protected function createThumb()
	{
		$image = Nette\Utils\Image::fromFile($this->src);
		$image->resize($this->width, $this->height, $this->crop ? Nette\Utils\Image::EXACT : Nette\Utils\Image::FIT);
		$image->save($this->desc);
	}

}
