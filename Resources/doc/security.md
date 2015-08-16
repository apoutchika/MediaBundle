Security
========

The media is only added by admin with role :
--------------------------------------------

Simply block media route in access_control, according to your logic :

```yaml
# app/config/security.yml
security:
    access_control:
        # ...
        - { path: ^/apoutchikamedia, role: ROLE_ADMIN }
        # ...
```


Set context by user, group or other :
-------------------------------------

When you generate you'r form, add filter parameter (default is null) :

```php
$form
    ->add('medias', 'apoutchika_media_one', array(
        'filter' => 'user:' $this->getUser()->getId(),
    ))
    ;
```
If user id is 42 :

* In this fied, only medias with filter 'user:42' is visible.
* If the user add new media in this field, the media has 'user:42' filter.
* Other filters is not visible here (null, product:5, session:11, etc.)
* You can has many fileds with various filters on same page


Create media without form
--------------------------

```php
<?php
$mediaManager = $this->get('apoutchika_media.manager.media');

$media = new Media;
$media
    ->setFile(__DIR__.'/logo.png')
    ->setFilter('user:'.$this->getUser()->getId())
    ;

$mediaManager->save($media);
```

Navigate :
----------

* [Installation](install.md)
* [Quick start](quickstart.md)
* [Contexts](contexts.md)
* [Render media](rendermedia.md)
* Security
* [Gaufrette](gaufrette.md)
* [Ckeditor](ckeditor.md)

* [Exemple](exemple.md)
* [Configuration reference](configuration_reference.md)
