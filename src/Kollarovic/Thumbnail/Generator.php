<?php

declare(strict_types=1);

namespace Kollarovic\Thumbnail;

use Nette\Utils\Image;


/**
 * @author  Mario Kollarovic
 *
 * Generator
 */
class Generator extends AbstractGenerator
{

	protected function createThumb(): void
	{
		$image = Image::fromFile($this->src);
		$image->resize($this->width, $this->height, $this->crop ? Image::EXACT : Image::FIT);
		$image->save($this->desc);
	}

}
