<?php

namespace ThumbnailHelper;

use Nette,
	Nette\Http\IRequest;


/**
* @author  Mario Kollarovic
*
* AbstractHelper
*/
abstract class AbstractHelper extends Nette\Object
{
	
	/** @var string */
	private $wwwDir;

	/** @var Nette\Http\IRequest */
	private $httpRequest;

	/** @var string */
	private $thumbPathMask;

	/** @var string */
	private $placeholder;


	/**
	 * @param string
	 * @param Nette\Http\IRequest
	 * @param string
	 * @param string
	 */
	function __construct($wwwDir, IRequest $httpRequest, $thumbPathMask, $placeholder)
	{
		$this->wwwDir = $wwwDir;
		$this->httpRequest = $httpRequest;
		$this->thumbPathMask = $thumbPathMask;
		$this->placeholder = $placeholder;
	}


	/**
	 * @param string
	 * @param int
	 * @param int
	 * @return string
	 */
	public function thumbnail($src, $width, $height = NULL)
	{
		$srcAbsPath = $this->wwwDir . '/' . $src;

		if (!is_file($srcAbsPath)) {
			return $this->createPlaceholderPath($src, $width, $height);
		}

		$thumbRelPath = $this->createThumbPath($srcAbsPath, $width, $height);
		$thumbAbsPath = $this->wwwDir . '/' . $thumbRelPath;

		if (!file_exists($thumbAbsPath) or (filemtime($thumbAbsPath) < filemtime($srcAbsPath))) {

			$dir = dirname($thumbAbsPath);
			if (!is_dir($dir)) {
				mkdir($dir, 0777, true);
			}

			$this->createThumb($srcAbsPath, $thumbAbsPath, $width, $height);
			clearstatcache();
		}

		return $this->httpRequest->url->basePath . $thumbRelPath;
	}


	/**
	 * @param string
	 * @param string
	 * @param int
	 * @param int
	 * @return void
	 */
	abstract protected function createThumb($src, $desc, $width, $height);


	/**
	 * @param string
	 * @param int
	 * @param int
	 * @return string
	 */
	private function createThumbPath($file, $width, $height)
	{
		$pathinfo = pathinfo($file);
		$search = array('{width}', '{height}', '{filename}', '{extension}');
		$replace = array($width, $height, $pathinfo['filename'], $pathinfo['extension']);
		return str_replace($search, $replace, $this->thumbPathMask);
	}


	/**
	 * @param string
	 * @param int
	 * @param int
	 * @return string
	 */
	private function createPlaceholderPath($src, $width, $height)
	{
		$width = $width===NULL ? $height : $width;
		$height = $height===NULL ? $width : $height;
		$search = array('{width}', '{height}', '{src}');
		$replace = array($width, $height, $src);
		return str_replace($search, $replace, $this->placeholder);
	}

}
