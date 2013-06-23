Usage
------

```php
{var $image='images/image.jpg'}

<img src="{$image|thumbnail: 150, 150}" />

```

Installation
-------------

composer.json

```json
{
    "require":{
        "kollarovic/thumbnail": "dev-master"
    }
}

```

bootstrap.php


```php
$configurator->onCompile[] = function ($configurator, $compiler) {
  $compiler->addExtension('thumbnail', new Kollarovic\Thumbnail\DI\Extension);
};

```

presenter

```php

abstract class BasePresenter extends Nette\Application\UI\Presenter
{

  /** @var \Kollarovic\Thumbnail\AbstractGenerator */
	protected $thumbnailGenerator;


	public function injectThumbnail(\Kollarovic\Thumbnail\AbstractGenerator $thumbnailGenerator)
	{
		$this->thumbnailGenerator = $thumbnailGenerator;
	}


	protected function createTemplate($class = NULL)
	{
		$template = parent::createTemplate($class);
		$template->registerHelper('thumbnail', $this->thumbnailGenerator->thumbnail);
		return $template;
	}
}

```

  
Configuration
-------------

config.neon

```yaml

common:
  thumbnail:
		thumbPathMask: 'images/thumbs/{filename}-{width}x{height}.{extension}'
		placeholder: 'http://dummyimage.com/{width}x{height}/efefef/f00&text=Image+not+found'
    
```
