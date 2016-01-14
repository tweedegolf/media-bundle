# TweedeGolf MediaBundle

The TweedeGolf MediaBundle provides a ready-to-use media manager for your Symfony2 project. Although it is especially designed to work with tinyMCE, the media bundle could be used for several other purposes.

![screenshot of the media manger modal](https://raw.githubusercontent.com/tweedegolf/media-bundle/master/doc/screen.png)

In essence, the TweedeGolf MediaBundle provides a File Entity and a controller that implements a JSON API for this entity

## Dependencies

This repository only provides the Symfony2 bundle and can be installed via [composer](https://getcomposer.org/). The front-end scripts used to display the modal can be found on [https://github.com/tweedegolf/media-browser](https://github.com/tweedegolf/media-browser) and can be installed using [bower](http://bower.io).

Composer dependencies:

* [StofDoctrineExtensionsBundle](https://github.com/stof/StofDoctrineExtensionsBundle)
* [VichUploaderBundle](https://github.com/dustin10/VichUploaderBundle)
* [LiipImagineBundle](https://github.com/liip/LiipImagineBundle)

Bower dependencies:

* [TweedeGolfMediaBrowser](https://github.com/tweedegolf/media-browser)

## Installation and configuration
A good starting point to setup a symfony2 project with [bower](http://bower.io) and [gulp](http://gulpjs.com/) is [the TweedeGolf Symfony Okoa Project](https://github.com/tweedegolf/symfony-okoa).

Using [Composer](https://getcomposer.org/) add the bundle to your requirements:

```json
{
    "require": {
        "tweedegolf/media-bundle": "0.1.*"
    }
}
```

Then run `composer update tweedegolf/media-bundle`

For installation instructions of the [media browser, see the repository](https://github.com/tweedegolf/media-browser).

### Basic configuration
Define mappings in your configuration file `app/config/config.yml`:

You can configure the maximum number of items displayed per page.

```yaml
tweede_golf_media:
    max_per_page: 24  # default is 18
```

Also, the MediaBundle depends on some configurations in other bundles. There needs to be a VichUploader mapping defined with the name `tgmedia_files`. Furthermore, there has to be a LiipImagine filter with the name `tgmedia_thumbnail`.

A [example configuration of these bundles](doc/config.md) is available in the documentation.

### Add routes to your routing file
In `app/config/routing.yml`, add the routes for the media bundle api:

```yaml
tweedegolf_media:
    resource: "@TweedeGolfMediaBundle/Controller/"
    type:     annotation
    prefix:   /api
```

Make sure the path to the bundle matches the path specified in the javascript source when including the media browser. Thus `/api` should match the firts part in the path speciefied when including the javascript source:

```javascript
var media_callback = require('tweedegolf-media-browser').tinymce_callback('/api/modal');
```

### Add the bundle to your AppKernel
Finally add the bundle in `app/AppKernel.php`:

```php
public function registerBundles()
{
    return array(
        // ...
        new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
        new Vich\UploaderBundle\VichUploaderBundle(),
        new Liip\ImagineBundle\LiipImagineBundle(),
        new TweedeGolf\MediaBundle\TweedeGolfMediaBundle(),
        // ...
    );
}
```
