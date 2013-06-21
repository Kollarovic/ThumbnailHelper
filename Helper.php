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
	 * @param string
	 * @param string
	 * @param int
	 * @param int
	 * @return void
 	 */
	protected function createThumb($src, $desc, $width, $height)
	{
		$image = Nette\Image::fromFile($src);
		$image->resize($width, $height);
		$image->save($desc);
	}

}
