<?php

namespace TweedeGolf\MediaBundle\Entity;

use TweedeGolf\MediaBundle\Entity\File;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper ;
use Liip\ImagineBundle\Imagine\Cache\Manager as ImagineCacheManager;

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
        $path = $this->vich->asset($file, 'file');

        return [
            'name' => $file->getFileName(),
            'path' => $path,
            'thumb' => $this->imagine->getBrowserPath($path, 'thumbnail'),
            'size' => $file->getFile()->getSize(),
            'type' => $file->getFile()->getMimeType(),
        ];
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
}
