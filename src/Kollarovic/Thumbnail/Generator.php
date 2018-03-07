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
	 * @throws Nette\Utils\UnknownImageFileException
	 * @throws \ImagickException
	 */
    protected function createThumb()
    {
        set_time_limit(1800);
        $finfo = new \SplFileInfo($this->src);
        $ext = Nette\Utils\Strings::lower($finfo->getExtension());
        if ($ext === 'gif' and class_exists(\Imagick::class)) {
            $image = new \Imagick($this->src);
            $image = $image->coalesceImages();
            foreach ($image as $frame) {
                if ($this->crop) {
                    $frame->cropThumbnailImage($this->width, $this->height);
                } else {
                    $frame->thumbnailImage($this->width, $this->height, TRUE);
                }
                $frame->setPage($this->width, $this->height, 0, 0);
            }
            $image = $image->deconstructImages();

            $image->writeImages($this->desc, TRUE);
        } else {
            $image = Nette\Utils\Image::fromFile($this->src);
            $image->resize($this->width, $this->height, $this->crop ? Nette\Utils\Image::EXACT : Nette\Utils\Image::FIT);
            $image->save($this->desc);
        }
    }

}
