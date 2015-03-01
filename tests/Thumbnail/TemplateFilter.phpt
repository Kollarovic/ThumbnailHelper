<?php

namespace Kollarovic\Thumbnail\Test;

use Tester\Assert;
use Mockery;

require_once __DIR__ . '/../bootstrap.php';


class TemplateFilterTest extends TestCase
{


	protected function setUp()
	{
		$this->createWeb();
	}


	protected function tearDown()
	{
		$this->deleteWeb();
	}


	public function testFilter()
	{
		$template = $this->createTemplate();
		$template->setFile(__DIR__ . '/template.latte');
		$output = (string)$template;
		Assert::equal('/images/thumbs/image-150x150.jpg', $output);
		Assert::true(file_exists($this->wwwDir . $output));
	}


	private function createTemplate()
	{
		$container = $this->createContainer();
		$mockControl = Mockery::mock('Nette\Application\UI\Control');
		$mockControl->shouldReceive('getPresenter')->andReturnNull();
		$mockControl->shouldReceive('templatePrepareFilters');
		return $container->getByType('Nette\Application\UI\ITemplateFactory')->createTemplate($mockControl);
	}
}


\run(new TemplateFilterTest());