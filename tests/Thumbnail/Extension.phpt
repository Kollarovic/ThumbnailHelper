<?php

namespace Kollarovic\Thumbnail\Test;

use Tester\Assert;


require_once __DIR__ . '/../bootstrap.php';


class ExtensionTest extends TestCase
{


	public function testGenerate()
	{
		$this->createWeb();
		$generator = $this->createGenerator();
		$path = $generator->thumbnail('images/image.jpg', 150, 150);
		Assert::same('/images/thumbs/image-150x150.jpg', $path);
		Assert::true(file_exists($this->wwwDir . $path));
		$this->deleteWeb();
	}


	public function testPlaceholder()
	{
		$generator = $this->createGenerator();
		$path = $generator->thumbnail('images/none.jpg', 150, 150);
		Assert::same('http://dummyimage.com/150x150/efefef/f00&text=Image+not+found', $path);
	}


	private function createGenerator()
	{
		$container = $this->createContainer();
		return $container->getByType('Kollarovic\Thumbnail\AbstractGenerator');
	}

}


\run(new ExtensionTest());