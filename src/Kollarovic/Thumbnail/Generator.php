<?php

namespace Kollarovic\Thumbnail;

use Nette\Utils\Image;


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
		$image = Image::fromFile($this->src);
		$image->resize($this->width, $this->height, $this->crop ? Image::EXACT : Image::FIT);
		$image->save($this->desc);
	}

}
