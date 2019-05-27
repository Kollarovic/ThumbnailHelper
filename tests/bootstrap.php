<?php


if (!$autoload = @include __DIR__ . '/../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer update --dev`';
	exit(1);
}

$autoload->addPsr4('Kollarovic\Thumbnail\Test\\', __DIR__ . '/Thumbnail');

define('TEMP_DIR', __DIR__ . '/temp');
Nette\Utils\FileSystem::delete(TEMP_DIR . '/cache');

Tester\Environment::setup();

function run(Tester\TestCase $testCase) {
	$testCase->run(isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : NULL);
}
