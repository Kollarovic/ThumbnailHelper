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

Installation via config.neon (nette 2.2+)
-----------------------------------------

```yaml

# nette 2.2+
extensions:
	thumbnail: Kollarovic\Thumbnail\DI\Extension
```

Register latte filter |thumbnail
```yaml

services:
	nette.latteFactory:
		setup:
			- addFilter(thumbnail, [@thumbnail.thumbnail, thumbnail])
```

Configuration
-------------

config.neon

```yaml

thumbnail:
	thumbPathMask: 'images/thumbs/{filename}-{width}x{height}.{extension}'
	placeholder: 'http://dummyimage.com/{width}x{height}/efefef/f00&text=Image+not+found'
    
```

MD5 thumbnail saving
--------------------
`{md5}` param in `thumbPathMask` takes file path and file name, converts it to md5 hash and then saves it in nested directories to avoid having millions of files in one folder. For example let's say md5 hash of my src location `somedir/project/www/images/users/1/profile.jpg` is e728fdeab7e2edda33f36fbf7a2b7c82 so using this `thumbPathMask`:

```yaml

thumbPathMask: 'images/thumbs/{md5}/{width}x{height}-{crop}.{extension}'

```

it gets stored in `images/thumbs/e/7/2/e728fdeab7e2edda33f36fbf7a2b7c82/{width}x{height}-{crop}.jpg`
