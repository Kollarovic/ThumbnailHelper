<?php

namespace ThumbnailHelper;

use Nette;


/**
* @author  Mario Kollarovic
*
* Helper
*/
class Helper extends AbstractHelper
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
