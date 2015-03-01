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

config.neon

```yaml


extensions:
	thumbnail: Kollarovic\Thumbnail\DI\Extension
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
