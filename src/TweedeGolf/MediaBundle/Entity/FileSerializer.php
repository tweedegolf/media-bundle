<?php

namespace TweedeGolf\MediaBundle\Entity;

use TweedeGolf\MediaBundle\Entity\File;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper ;
use Liip\ImagineBundle\Imagine\Cache\CacheManager as ImagineCacheManager;

class FileSerializer
{

    /**
     * @var UploaderHelper
     */
    private $vich;


    /**
     * @var ImagineCacheManager
     */
    private $imagine;

    /**
     * @param Translator $vich
     */
    public function __construct(UploaderHelper $vich, ImagineCacheManager $imagine)
    {
        $this->vich = $vich;
        $this->imagine = $imagine;
    }

    /**
     * @param File $File
     * @return array
     */
    public function serialize(File $file)
    {
        $data = [
            'id'   => $file->getId(),
            'name' => $file->getFileName(),
            'path' => $this->vich->asset($file, 'file'),
            'size' => $this->formatSize($file->getFile()->getSize()),
            'mime' => $file->getFile()->getMimeType(),
        ];

        if ($file->isImage()) {
            $fileName = $file->getFile()->getFileName();
            $data['thumb'] = $this->imagine->getBrowserPath($fileName, 'tgmedia_thumbnail', true);
        } else {
            $data['type'] = $file->getExtension();
        }

        return $data;
    }

    /*
     * @param array File
     * @return array
     */
    public function serializeAll(array $files)
    {
        $data = [];

        foreach($files as $file) {
            $data[] = $this->serialize($file);
        }

        return $data;
    }

    protected function formatSize($bytes)
    {
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB'];

        if ($bytes) {
            $i = floor(log($bytes, 1024));
            return round($bytes / pow(1024, $i), 2) . ' ' . $sizes[$i];
        }

        return '0 B';
    }
}
