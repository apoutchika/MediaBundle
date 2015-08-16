Render Media
===========

Basic render
------------

Same quick start, for render media you can use :

```htmljango
{{ media|media_html }}
```

Or, if you wan't only url/path :

In twig :
```htmljango
{# ex: http://www.exemple.tld/medias/original/sha1filename.jpg #}
{{ media|media_abs_url }}

{# ex: medias/original/sha1filename.jpg #}
{# if not local filesystem, return absolute url #}
{{ media|media_url }}

{# ex: /home/user/www/exemple.tld/web/medias/original/sha1filename.jpg #}
{# only for local filesystem #}
{{ media|media_path }}
```

In controller :
```php
<?php

    $media = $yourEntity->getMedia();
    $mediaManager = $this->get('apoutchika_media.manager.media');

    // ex: medias/original/sha1filename.jpg
    // if not local filesystem, return absolute url
    $relativeUrl = $mediaManager->getUrl($media);

    // ex: http://www.exemple.tld/medias/original/sha1filename.jpg
    $url = $mediaManager->getAbsoluteUrl($media);

    // ex: /home/user/www/exemple.tld/web/medias/original/sha1filename.jpg
    // only for local filesystem
    $path = $mediaManager->getPath($media);
    
```

Images render
-------------

You have many possiblity for show images :

### Use json parameters :

You can use width, height and/or focus parameter
```htmljango
{#
    ex: 
    media.width : 1000
    media.height: 500
#} 

{# resize to 100x50 #}
{{ media|media_html({width: 100}) }}

{# resize to 200x100 #}
{{ media|media_html({height: 100}) }}

{# resize to 100x50 #}
{{ media|media_html({width: 100, height: 100}) }}

{# crop to 100x100 by focus #}
{{ media|media_html({width: 100, height: 100, focus: true}) }}
```
Look at the tests to understand the crop/resize images.


Or, it's work with media_url :
```htmljango
<img src="{{ media|media_url('100x100_focus') }}" alt="{{ media.alt }}" />
<img src="{{ media|media_url({width: 100, height: 100, focus: true}) }}" alt="{{ media.alt }}" />
```



### Use name of action :

The name of action is defined by : [width]x[height]_focus

Exemple with previous json :
```htmljango
{{ media|media_html({width: 100}) }}
{{ media|media_html('100x') }}

{{ media|media_html({height: 100}) }}
{{ media|media_html('x100') }}

{{ media|media_html({width: 100, height: 100}) }}
{{ media|media_html('100x100') }}

{{ media|media_html({width: 100, height: 100, focus: true}) }}
{{ media|media_html('100x100_focus') }}
```


### Use alias :

You can set alias in config.yml :
```yaml
apoutchika_media:
    alias:
        header: { width: 1000, height: 100, focus: false }
        product: { width: 200, height: 190, focus: true }
        footer: { height: 100 }
```
And use it :
```htmljango
{{ media|media_html('header') }}
```

Navigate :
----------

* [Installation](install.md)
* [Quick start](quickstart.md)
* [Contexts](contexts.md)
* Render media
* [Security](security.md)
* [Gaufrette](gaufrette.md)
* [Ckeditor](ckeditor.md)

* [Exemple](exemple.md)
* [Configuration reference](configuration_reference.md)
