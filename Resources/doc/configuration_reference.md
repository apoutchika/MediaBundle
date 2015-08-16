ApoutchikaMediaBundle Configuration Reference
=============================================

```yaml
apoutchika_media:
    media_class: ~ # Required
    driver: gd
    original_dir: original

    # you can change css style
    css: ~ # default is in bundles/apoutchikamedia/css/main.css

    # if image > 1500px, resize it
    limit: ~ # ex: 1000 # Optional

    include:
        jquery: true
        jqueryui: true
        underscore: true
        backbone: true
        backbonejjrelational: true
        mustache: true
        dropzone: true
        jcrop: true
    trusted_extensions: [doc, xls, txt, pdf, rtf, docx, xlsx, ppt, pptx, odt, odg, odp, ods, odc, odf, odb, csv, xml, gif, jpg, jpeg, png, svg, mp3, ogg, mp4, avi, mpg, mpeg, ogv, webm, zip, tar, gz, 7z, rar]
    filesystems: ~ # Required (see gaufrette.md for configuration)
    contexts:
        default: [doc, xls, txt, pdf, rtf, docx, xlsx, ppt, pptx, odt, odg, odp, ods, odc, odf, odb, csv, xml, gif, jpg, jpeg, png, svg, mp3, ogg, mp4, avi, mpg, mpeg, ogv, webm, zip, tar, gz, 7z, rar]
    alias: ~
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

* [Exemple](exemple.md)
* Configuration reference
