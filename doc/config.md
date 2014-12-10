
# Example configuration

Example configuration of StofdoctrineExtensions, KnpGaufretteBunlde, VichUploaderBundle and the LiipImagineBundle for the TweedegolfMediaBundle.

    stof_doctrine_extensions:
        orm:
            default:
                timestampable: true

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
