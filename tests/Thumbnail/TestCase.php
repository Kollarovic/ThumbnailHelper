<?

namespace Kollarovic\Thumbnail\Test;

use Nette\Configurator;
use Nette\Utils\FileSystem;
use Nette\Utils\Image;
use Nette\Utils\Strings;
use Tester\Helpers;
use Mockery;


abstract class TestCase extends \Tester\TestCase
{


	/** @var string */
	protected $wwwDir;


	function __construct()
	{
		$this->wwwDir = TEMP_DIR . '/' . Strings::webalize(get_called_class());
	}


	protected function createWeb()
	{
		Helpers::purge($this->wwwDir);
		Helpers::purge($this->wwwDir . '/images');
		$image = Image::fromBlank(500, 500);
		$image->save($this->wwwDir . '/images/image.jpg');
	}


	protected function deleteWeb()
	{
		FileSystem::delete($this->wwwDir);
	}


	protected function createContainer()
	{
		$configurator = new Configurator();
		$configurator->addParameters(array('wwwDir' => $this->wwwDir));
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../config.neon');
		return $configurator->createContainer();
	}


	function createHttpRequest()
	{
		$httpRequest = Mockery::mock('Nette\Http\Request');
		$url = Mockery::mock('Nette\Http\UrlScript');
		$url->shouldReceive('getBasePath')->andReturn('/');
		$httpRequest->shouldReceive('getUrl')->andReturn($url);
		return $httpRequest;
	}

}
