<?php

namespace Kollarovic\Thumbnail\Test;

use Kollarovic\Thumbnail\Generator;
use Nette\Http\Request;
use Tester\Assert;


require_once __DIR__ . '/../bootstrap.php';


class PlaceholderTest extends TestCase
{

	/** @var Request */
	private $httpRequest;


	function __construct()
	{
		parent::__construct();
		$this->httpRequest = $this->createHttpRequest();
	}


	/**
	 * @dataProvider getPlaceholderData
	 */
	public function testPlaceholder($placeholderPath, $expected,  $width = 150, $height = 150)
	{
		$generator = new Generator($this->wwwDir, $this->httpRequest, '', $placeholderPath);
		$path = $generator->thumbnail('images/none.jpg', $width, $height);
		Assert::same($expected, $path);
	}


	public function testSrcPlaceholder()
	{
		$placeholderPath = 'http://placehold.it/{width}x{height}&text={src}+not+found';
		$generator = new Generator($this->wwwDir, $this->httpRequest, '', $placeholderPath);
		$path = $generator->thumbnail('images/none.jpg', 150, 150);
		Assert::match('~http://placehold.it/150x150&text=.*images/none.jpg\+not\+found~', $path);
	}


	protected function getPlaceholderData()
	{
		return [
			['http://dummyimage.com/{width}x{height}/efefef/f00&text=Image+not+found', 'http://dummyimage.com/150x150/efefef/f00&text=Image+not+found'],
			['http://placehold.it/{width}x{height}&text=Image+not+found', 'http://placehold.it/150x150&text=Image+not+found', 150, NULL],
			['http://placehold.it/{width}x{height}&text=Image+not+found', 'http://placehold.it/150x150&text=Image+not+found', NULL, 150],
			['/images/none.png', '/images/none.png'],
			['/images/none{width}x{height}.png', '/images/none150x150.png'],
		];
	}

}


\run(new PlaceholderTest());