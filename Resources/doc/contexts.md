Contexts
========


Configures global context :
---------------------------
```yaml
apoutchika_media:
    contexts:
        # If no context is specified, the default is used
        default: [doc, xls, txt, pdf, rtf, docx, xlsx, ppt, pptx, odt, odg, odp, ods, odc, odf, odb, csv, xml, gif, jpg, jpeg, png, svg, mp3, ogg, mp4, avi, mpg, mpeg, ogv, webm, zip, tar, gz, 7z, rar]
        # Set you'r context with : contextName: [arrayOfExtensions]
        musique: [mp3, ogg, wav]
        video: [avi, mp4, mpg, mpeg]
        image: [jpg, png, tiff, gif]
        pdf: [pdf]
        archive: [zip, rar, tar.gz, tar, 7z]

```

If you want allowed dangerous files (exe, sh, etc.), the config has trusted_extensions, if the
uploaded file don't in trusted extensions, the filename is suffixed by xxx.txt on the 
server (ex: sha1filename.exe.txt) :

```yaml
apoutchika_media:
    # here, exe and sh is not in array
    trusted_extensions: [mp3, ogg, wav, avi, mp4, mpg, mpeg, jpg, png]
    contexts:
        default: [mp3, ogg, wav, avi, mp4, mpg, mpeg, jpg, png, exe, sh]

        musique: [mp3, ogg, wav]
        video: [avi, mp4, mpg, mpeg]
        image: [jpg, png]

        # exe and sh is allowed in program context, but is renamed to exe.txt and sh.txt
        program: [exe, sh]
```


Set context in field :
----------------------
```php
<?php
    $form
        // Single context
        ->add('media', 'apoutchika_media_one', array(
            'contexts' => 'musique',
        ))

        // Many contexts
        ->add('media2', 'apoutchika_media_one', array(
            'contexts' => array('musique', 'video'),
        ))

        // Add extensions (musique context + video context + .zip + .gz + .rar)
        ->add('media2', 'apoutchika_media_one', array(
            'contexts' => array('musique', 'video'),
            'allowed_extensions' => array('zip', 'gz', 'rar'),
        ))
        ;
```

Navigate :
----------

* [Installation](install.md)
* [Quick start](quickstart.md)
* Contexts
* [Render media](rendermedia.md)
* [Security](security.md)
* [Gaufrette](gaufrette.md)
* [Ckeditor](ckeditor.md)

* [Exemple](exemple.md)
* [Configuration reference](configuration_reference.md)
