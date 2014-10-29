# TweedeGolf MediaBundle

The TweedeGolf MediaBundle provides a ready-to-use media manager for your Symfony2 project. Although it is especially designed to work with tinyMCE, the media bundle could be used for several other purposes.



In essence, the TweedeGolf MediaBundle provides a File Entity and a controller that implements a JSON API for this entity

This repository only provides the Symfony2 bundle. The front-end scripts used to display


Example configuration

    stof_doctrine_extensions:
        orm:
            default:
                timestampable: true

    tweedegolf_media:
        resource: "@TweedeGolfMediaBundle/Controller/"
        type:     annotation
        prefix:   /api

    knp_gaufrette:
        stream_wrapper: ~
        adapters:
            tgmedia_adapter:
                local:
                    directory: %kernel.root_dir%/../web/files
                    create: true
        filesystems:
            tgmedia_files:
                adapter: tgmedia_adapter

    vich_uploader:
        db_driver: orm
        gaufrette: true
        storage: vich_uploader.storage.gaufrette
        mappings:
            tgmedia_file:
                uri_prefix: /files
                upload_destination: tgmedia_files
                delete_on_remove: true
                delete_on_update: true
                inject_on_load: true
                namer: vich_uploader.namer_origname

    liip_imagine:
        data_loader: stream.tgmedia_files
        filter_sets:
            tgmedia_thumbnail:
                quality: 80
                filters:
                    thumbnail: { size: [200, 150], mode: outbound }

    services:
        main.data.loader.stream.loader:
            class: %liip_imagine.data.loader.stream.class%
            arguments:
                - @liip_imagine
                - gaufrette://tgmedia_files/
            tags:
                - { name: liip_imagine.data.loader, loader: stream.tgmedia_files }
