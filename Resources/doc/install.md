Installation
============

This is the basic installation/configuration for **Symfony2**

Install the bundle
------------------

```bash
composer require apoutchika/media-bundle:dev-sf2
```

Active In AppKernel
-------------------

```php
<?php

// app/AppKernel.php

public function registerBundles()
{
    return array(
        // ...
        new Apoutchika\MediaBundle\ApoutchikaMediaBundle(),
        // ...
    );
}
```


Add routes
----------

```yaml
# app/routing.yml

apoutchika_media:
    resource: "@ApoutchikaMediaBundle/Resources/config/routing.yml"
    prefix:   /apoutchikamedia
```

Create new media entity
-----------------------

```php
<?php

// src/Acme/DefaultBundle/Entity/Media.php

namespace Acme\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Apoutchika\MediaBundle\Model\Media as BaseMedia;

/**
 * Media
 *
 * @ORM\Table(name="apoutchika_media")
 * @ORM\Entity()
 */
class Media extends BaseMedia
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
    }
}
```


Configure it
------------

```yaml
# Active translator
framework:
    translator:      { fallback: "%locale%" }


# Add form type
twig:
    form:
        resources:
            - 'ApoutchikaMediaBundle:Form:fields.html.twig'


# Configure ApoutchikaMedia
apoutchika_media:
    # You'r media class
    media_class: Acme\DefaultBundle\Entity\Media

    # driver for imagine (gd|imagick|gmagick)
    # Default is gd
    driver: gd

    # if image > 1500px, resize it
    limit: 1500

    # Set false if the plugin is already add in your pages
    # Default is true => automatically add in your page
    include:
        jquery: true
        jqueryui: true
        underscore: true
        backbone: true
        backbonejjrelational: true
        mustache: true
        dropzone: true
        jcrop: true

    filesystems:
        # set local path for medias
        local:
            path: %kernel.root_dir%/../web/medias/
            url: http://www.exemple.tld/medias/
            url_relative: /medias/

            # if you are on developement enviromenent, 
            # or you don't have stardard url
            # you must set to true for replace relative url to absolute url
            force_absolute_url: false
```

Install assets
--------------

```bash
$ app/console assets:install web --symlink
```

Update database
--------------

```bash
$ app/console doctrine:schema:update --force
```
(Or use DoctrineMigrations)

Create the media directory and set Chmod
----------------------------------------

```bash
mkdir web/medias && chmod -R 777 web/medias
```


Navigate :
----------

* Installation
* [Quick start](quickstart.md)
* [Contexts](contexts.md)
* [Render media](rendermedia.md)
* [Security](security.md)
* [Gaufrette](gaufrette.md)
* [Ckeditor](ckeditor.md)

* [Exemple](exemple.md)
* [Configuration reference](configuration_reference.md)
