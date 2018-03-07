<?php

namespace Kollarovic\Thumbnail;

use Nette;


/**
 * @author  Mario Kollarovic
 *
 * AbstractGenerator
 */
abstract class AbstractGenerator
{
	use Nette\SmartObject;

	/** @var string */
	protected $src;

	/** @var string */
	protected $desc;

	/** @var int */
	protected $width;

	/** @var int */
	protected $height;

	/** @var bool */
	protected $crop;

	/** @var string */
	private $wwwDir;

	/** @var Nette\Http\IRequest */
	private $httpRequest;

	/** @var string */
	private $thumbPathMask;

	/** @var string */
	private $placeholder;

	/** @var string */
	protected $placeholderForCustomText;


	/**
	 * AbstractGenerator constructor.
	 * @param string $wwwDir
	 * @param Nette\Http\IRequest $httpRequest
	 * @param string $thumbPathMask
	 * @param string $placeholder
	 * @param string $placeholderForCustomText
	 */
	function __construct($wwwDir, Nette\Http\IRequest $httpRequest, $thumbPathMask, $placeholder, $placeholderForCustomText)
	{
		$this->wwwDir = $wwwDir;
		$this->httpRequest = $httpRequest;
		$this->thumbPathMask = $thumbPathMask;
		$this->placeholder = $placeholder;
		$this->placeholderForCustomText = $placeholderForCustomText;
	}


	/**
	 * @param string
	 * @param int
	 * @param int
	 * @param bool
	 * @param string
	 * @return string
	 */
	public function thumbnail($src, $width, $height = NULL, $crop = false, $placeholderText = NULL)
	{
		$this->src = $this->wwwDir . '/' . $src;
		$this->width = $width;
		$this->height = $height;
		$this->crop = $crop;

		if (!is_file($this->src)) {
			return $this->createPlaceholderPath($placeholderText);
		}

		$thumbRelPath = $this->createThumbPath();
		$this->desc = $this->wwwDir . '/' . $thumbRelPath;

		if (!file_exists($this->desc) or (filemtime($this->desc) < filemtime($this->src))) {
			$this->createDir();
			try {
				$this->createThumb();
			} catch (\Exception $e) {
				return $this->createPlaceholderPath($placeholderText);
			}
			clearstatcache();
		}

		return $this->httpRequest->url->basePath . $thumbRelPath;
	}


	/**
	 * @return void
	 */
	abstract protected function createThumb();


	/**
	 * @return void
	 */
	private function createDir()
	{
		$dir = dirname($this->desc);
		if (!is_dir($dir)) {
			mkdir($dir, 0777, true);
		}
	}


	/**
	 * @return string
	 */
	private function createThumbPath()
	{
		$pathinfo = pathinfo($this->src);
		$md5 = md5($this->src);
		$md5Dir = $md5[0] . "/" . $md5[1] . "/" . $md5[2] . "/" . $md5;
		$search = array('{width}', '{height}', '{crop}', '{filename}', '{extension}', "{md5}");
		$replace = array($this->width, $this->height, (int)$this->crop, $pathinfo['filename'], $pathinfo['extension'], $md5Dir);
		return str_replace($search, $replace, $this->thumbPathMask);
	}


	/**
	 * @param string
	 * @return string
	 */
	private function createPlaceholderPath($placeholderText = NULL)
	{
		$width = $this->width === NULL ? $this->height : $this->width;
		$height = $this->height === NULL ? $this->width : $this->height;
		if ($placeholderText === NULL) {
			$search = array('{width}', '{height}', '{src}');
			$replace = array($width, $height, $this->src);
			return str_replace($search, $replace, $this->placeholder);
		} else {
			$search = array('{width}', '{height}', '{src}', '{text}');
			$replace = array($width, $height, $this->src, $placeholderText);
			return str_replace($search, $replace, $this->placeholderForCustomText);
		}
	}
}
