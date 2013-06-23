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
		$image = Nette\Image::fromFile($this->src);
		$image->resize($this->width, $this->height);
		$image->save($this->desc);
	}

}
