<?php

namespace Kollarovic\Thumbnail\Test;

use Kollarovic\Thumbnail\Generator;
use Nette\Http\Request;
use Nette\Utils\Image;
use Tester\Assert;


require_once __DIR__ . '/../bootstrap.php';


class GeneratorTest extends TestCase
{

	/** @var Request */
	private $httpRequest;


	function __construct()
	{
		parent::__construct();
		$this->httpRequest = $this->createHttpRequest();
	}


	protected function setUp()
	{
		$this->createWeb();
	}


	protected function tearDown()
	{
		$this->deleteWeb();
	}


	/**
	 * @dataProvider getPathData
	 */
	public function testGenerate($thumbPathMask, $expected, $width = 150, $height = 150, $crop = FALSE)
	{
		$generator = new Generator($this->wwwDir, $this->httpRequest, $thumbPathMask, '');
		$path = $generator->thumbnail('images/image.jpg', $width, $height, $crop);
		Assert::same($expected, $path);
		Assert::true(file_exists($this->wwwDir . $path));

		$image = Image::fromFile($this->wwwDir . $path);
		$width and Assert::equal($width, $image->width);
		$height and Assert::equal($height, $image->height);
	}


	public function testGenerateMd5()
	{
		$thumbPathMask = 'images/thumbs/{md5}/{width}x{height}-{crop}.{extension}';
		$generator = new Generator($this->wwwDir, $this->httpRequest, $thumbPathMask, '');
		$path = $generator->thumbnail('images/image.jpg', 150, 150);
		Assert::match('~/images/thumbs/./././[^/]{32}/150x150-0.jpg~', $path);
		Assert::true(file_exists($this->wwwDir . $path));
	}


	protected function getPathData()
	{
		return [
			['images/{filename}-{width}x{height}.{extension}', '/images/image-150x150.jpg'],
			['images/{filename}-{width}x{height}.{extension}', '/images/image-150x.jpg', 150, NULL],
			['images/{filename}-{width}x{height}.{extension}', '/images/image-x150.jpg', NULL, 150],
			['images/{filename}-{width}x{height}-{crop}.{extension}', '/images/image-150x150-0.jpg'],
			['images/{filename}-{width}x{height}-{crop}.{extension}', '/images/image-120x120-1.jpg', 120, 120, TRUE],
			['images/cache/{width}x{height}/{filename}.{extension}', '/images/cache/150x150/image.jpg'],
		];
	}


}


\run(new GeneratorTest());