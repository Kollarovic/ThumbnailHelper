<?php

namespace Kollarovic\Thumbnail;

use Nette;
use    Nette\Http\IRequest;


/**
 * @author  Mario Kollarovic
 *
 * AbstractGenerator
 */
abstract class AbstractGenerator
{

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

	/** @var IRequest */
	private $httpRequest;

	/** @var string */
	private $thumbPathMask;

	/** @var string */
	private $placeholder;


	/**
	 * @param string
	 * @param IRequest
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
	 * @param bool
	 * @return string
	 */
	public function thumbnail($src, $width, $height = NULL, $crop = false)
	{
		$this->src = $this->wwwDir . '/' . $src;
		$this->width = $width;
		$this->height = $height;
		$this->crop = $crop;

		if (!is_file($this->src)) {
			return $this->createPlaceholderPath();
		}

		$thumbRelPath = $this->createThumbPath();
		$this->desc = $this->wwwDir . '/' . $thumbRelPath;

		if (!file_exists($this->desc) or (filemtime($this->desc) < filemtime($this->src))) {
			$this->createDir();
			$this->createThumb();
			clearstatcache();
		}

		return $this->httpRequest->getUrl()->basePath . $thumbRelPath;
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
	 * @return string
	 */
	private function createPlaceholderPath()
	{
		$width = $this->width === NULL ? $this->height : $this->width;
		$height = $this->height === NULL ? $this->width : $this->height;
		$search = array('{width}', '{height}', '{src}');
		$replace = array($width, $height, $this->src);
		return str_replace($search, $replace, $this->placeholder);
	}

}
