Gaufrette
========

In your config.yml, the first filesystem is the master, and next is
slaves. 

```yaml
apoutchika_media:
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
            force_absolute_url: false
```

Actually, only local and ftp adapters work in apoutchikaMediaBundle. 
If you want add new, see in Filesystem directory, add new is easy.
Please, if is work, send pull request for share it !

Navigate :
----------

* [Installation](install.md)
* [Quick start](quickstart.md)
* [Contexts](contexts.md)
* [Render media](rendermedia.md)
* [Security](security.md)
* Gaufrette
* [Ckeditor](ckeditor.md)

* [Exemple](exemple.md)
* [Configuration reference](configuration_reference.md)
