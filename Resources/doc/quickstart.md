Quick start
===========

[You have install and configure it before ?](install.md)


Add relation on your Entity
---------------------------

```php
<?php

// src/Acme/DefaultBundle/Entity/Product.php

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity()
 */
class Product
{
    //...

    /**
     * @ORM\ManyToOne(targetEntity="\Kamakle\MediaBundle\Entity\Media")
     */
    private $media;

    /**
     * @ORM\ManyToMany(targetEntity="\Kamakle\MediaBundle\Entity\Media")
     */
    private $medias;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
    }

    //...
}
```


Create your form
----------------

```php
<?php
// in you'r controller or in your form type

$form
    // for relation ManyToOne
    ->add('music', 'apoutchika_media_one')

    // For relation ManyToMany
    ->add('pdfs', 'apoutchika_media_many')
    ;
```

Render Form
------------
```htmljango
{{ form_row (form.music) }}
{{ form_row (form.pdfs) }}
```


Show the media
--------------

```htmljango
{{ media|media_html }}
```


Create media without form
--------------------------

```php
<?php
$mediaManager = $this->get('apoutchika_media.manager.media');

$media = new Media;
$media->setFile(__DIR__.'/logo.png');

$mediaManager->save($media);
```


It's quick start, see next doc for size configuration, and more!

Navigate :
----------

* [Installation](install.md)
* [Quick start](quickstart.md)
* [Contexts](contexts.md)
* [Render media](rendermedia.md)
* [Security](security.md)
* [Gaufrette](gaufrette.md)
* [Ckeditor](ckeditor.md)

* [Exemple](exemple.md)
* [Configuration reference](configuration_reference.md)
