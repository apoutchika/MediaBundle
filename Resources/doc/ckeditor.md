ApoutchikaMediaBundle CKEditor
==============================

1) Install CKEditor
-------------------

We suggest use [egeloen/IvoryCKEditorBundle](https://github.com/egeloen/IvoryCKEditorBundle)

See her documentation..

2) Set route in ckeditor form
------------------------------

```php
<?php

$form
    ->add('description', 'ckeditor', array(
        'config_name' => 'my_config',
        'config' => array (
            'filebrowserBrowseRoute' => 'apoutchika_media_ckeditor_image',
            'filebrowserBrowseAbsolute' => true,
        );
```



3) Use custom filter (Optionnal)
--------------------------------

If you wan't add security, you can create you'r controller for add you'r [filter](security.md).


Create the route :

```yaml
acme_default_ckeditor:
    path:     /ckeditor
    defaults: { _controller: AcmeDefaultBundle:CKEditor:show }
    requirements:
        _method: GET
```


Add the controller:

```php
<?php

class CKEditorController extends Controller
{
    public function showAction(Request $request)
    {
        // get you custom filter
        $filterKey = $this->get('apoutchika_media.filter')->set('user:'.$this->getUser()->getId());

        // render view
        return $this->render('ApoutchikaMediaBundle:CKEditor:image.html.twig', array(
            'filter' => $filterKey,
        ));
    }
}
```

Use the custom route in CKEditor field :

```php
<?php

$form
    ->add('description', 'ckeditor', array(
        'config_name' => 'my_config',
        'config' => array (
            'filebrowserBrowseRoute' => 'acme_default_ckeditor',
            'filebrowserBrowseAbsolute' => true,
        );
```

Navigate :
----------

* [Installation](install.md)
* [Quick start](quickstart.md)
* [Contexts](contexts.md)
* [Render media](rendermedia.md)
* [Security](security.md)
* [Gaufrette](gaufrette.md)
* Ckeditor

* [Exemple](exemple.md)
* [Configuration reference](configuration_reference.md)
