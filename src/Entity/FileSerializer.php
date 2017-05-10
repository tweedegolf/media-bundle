<?php

namespace TweedeGolf\MediaBundle\Entity;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Liip\ImagineBundle\Imagine\Cache\CacheManager as ImagineCacheManager;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use TweedeGolf\MediaBundle\Model\AbstractFile;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * This class is responsible for serializing a file
 * Converts a file entity to an associative array and adds
 * extra fields like an URL to the thumbnail image.
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
     * Serialize a single file.
     *
     * @param AbstractFile $File
     *
     * @return array
     */
    public function serialize(AbstractFile $file)
    {
        $fileFile = $file->getFile();
        $data = [
            'id' => $file->getId(),
            'name' => $file->getFileName(),
            'path' => $this->vich->asset($file, 'file'),
            'size' => $this->formatSize(is_null($fileFile) ? $file->getFileSize() : $fileFile->getSize()),
            'mime' => is_null($fileFile) ? $file->getMimeType() : $fileFile->getMimeType(),
        ];

        if ($file->isImage()) {
            $fileName = $this->vich->asset($file, 'file');
            $data['thumb'] = $this->imagine->getBrowserPath($fileName, 'tgmedia_thumbnail');
        } else {
            $data['type'] = $file->getExtension();
        }

        return $data;
    }

    /**
     * Serialize a list of files.
     *
     * @param Paginator $paginator
     *
     * @return array
     */
    public function serializeAll(Paginator $paginator)
    {
        $data = [];

        foreach ($paginator as $file) {
            $data[] = $this->serialize($file);
        }

        return $data;
    }

    /**
     * Convert a size in bytes to a human readable representation.
     *
     * @param int $bytes number of bytes
     */
    protected function formatSize($bytes)
    {
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB'];

        if ($bytes) {
            $i = floor(log($bytes, 1024));
            if (!isset($sizes[$i])) {
                return 'ultra huge';
            }

            return round($bytes / pow(1024, $i), 2).' '.$sizes[$i];
        }

        return '0 B';
    }
}
