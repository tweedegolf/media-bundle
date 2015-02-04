<?php

namespace TweedeGolf\MediaBundle\Entity;

use TweedeGolf\MediaBundle\Entity\File;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper ;
use Liip\ImagineBundle\Imagine\Cache\CacheManager as ImagineCacheManager;

/**
 * This class is responsible for serializing a file
 * Converts a file entity to an associative array and adds
 * extra fields like an URL to the thumbnail image
 */
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
     * Serialize a single file
     * @param File $File
     * @return array
     */
    public function serialize(File $file)
    {
        $data = [
            'id'   => $file->getId(),
            'name' => $file->getFileName(),
            'path' => $this->vich->asset($file, 'tgmedia_file'),
            'size' => $this->formatSize($file->getFile()->getSize()),
            'mime' => $file->getFile()->getMimeType(),
        ];

        if ($file->isImage()) {
            $fileName = $this->vich->asset($file, 'tgmedia_file');
            $data['thumb'] = $this->imagine->getBrowserPath($fileName, 'tgmedia_thumbnail');
        } else {
            $data['type'] = $file->getExtension();
        }

        return $data;
    }

    /**
     * Serialize a list of files
     * @param array File
     * @return array
     */
    public function serializeAll(array $files)
    {
        $data = [];

        foreach ($files as $file) {
            $data[] = $this->serialize($file);
        }

        return $data;
    }

    /**
     * Convert a size in bytes to a human readable representation
     * @param integer $bytes number of bytes
     */
    protected function formatSize($bytes)
    {
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB'];

        if ($bytes) {
            $i = floor(log($bytes, 1024));
            if (!isset($sizes[$i])) {
                return 'ultra huge';
            }
            return round($bytes / pow(1024, $i), 2) . ' ' . $sizes[$i];
        }

        return '0 B';
    }
}
