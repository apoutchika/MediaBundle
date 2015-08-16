Exemple
=======


Config.yml
----------
```yaml
apoutchika_media:
    media_class: Acme\DefaultBundle\Entity\Media
    driver: imagick

    # default is original, you can change here
    original_dir: default

    # if image > 1500px, resize it
    limit: 1500

    # Include not required for SonataAdminBundle
    include:
        jquery: false
        jqueryui: false

    # I wan't default extensions
    trusted_extensions: ~

    filesystems:

        # master
        ftp: 
            port: 21
            username: ftpuser
            password: pwd
            passive: false
            create: true
            ssl: false
            host: ftp.exemple.tld
            path: /medias/
            url: http://medias.exemple.fr/medias/

        # slave
        local:
            path: %kernel.root_dir%/../web/medias/
            url: http://www.exemple.tld/medias/
            url_relative: /medias/

    contexts:
        default: ~ # Default contexts
        musique: [mp3, ogg, wav]
        video: [avi, mp4, mpg, mpeg]
        image: [jpg, jpEg, png, tiff, gif]
        pdf: [pdf]

    alias:
        header: { width: 1000, height: 100, focus: false }
        product: { width: 200, height: 190, focus: true }
        footer: { height: 100 }
```

Form
----
```php
<?php
    $form
        ->add('logo', 'apoutchika_media_one', array(
            'label' => 'Select you\'r logo',
            'contexts' => array('image'), // set contexts
            'filter' => 'user:'.$user->getId(),
        ))
        ->add('pdfs', 'apoutchika_media_one', array(
            'label' => 'Select pdfs',
            'contexts' => array('pdf'), // set context
            'allowed_extensions' => array('zip', 'gz', 'rar'), // add custom extensions
            'filter' => 'user:'.$user->getId(),
        ))
        ;
```

Form
----
```htmljango
{{ form_row (form.logo) }}
{{ form_row (form.pdfs) }}
```


Show media in twig :
```htmljango
{{ entity.logo|media_html('header') }}
```

Navigate :
----------

* [Installation](install.md)
* [Quick start](quickstart.md)
* [Contexts](contexts.md)
* [Render media](rendermedia.md)
* [Security](security.md)
* [Gaufrette](gaufrette.md)
* [Ckeditor](ckeditor.md)

* Exemple
* [Configuration reference](configuration_reference.md)
