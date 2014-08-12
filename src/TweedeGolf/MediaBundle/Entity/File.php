<?php

namespace TweedeGolf\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\File as File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Attachment
 *
 * @ORM\Entity
 * @ORM\Table()
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass="Tg\MainBundle\Entity\Repository\DocumentRepository")
 */
class Document
{
    use TimestampableEntity;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\File(maxSize="50M")
     * @Vich\UploadableField(mapping="file", fileNameProperty="fileName")
     *
     * @var File $file
     */
    private $file;

    /**
     * @var string $fileName
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $fileName;

    /**
     * @var string $filesize
     * @ORM\Column(type="integer", nullable=false)
     */
    private $filesize;

    /**
     * @var string $mimetype
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $mimetype;

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->fileName;
    }

    /**
     * Set file
     *
     * @param File $file
     * @return $this
     */
    public function setFile(File $file = null)
    {
        $this->file = $file;
        $this->setUpdatedAt(new \DateTime());

        if ($file) {
            $this->mimetype = $file->getMimeType();
            $this->filesize = $file->getSize();
        } else {
            $this->mimetype = null;
            $this->filesize = null;
        }

        return $this;
    }

    /**
     * Get file
     *
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     * @return File
     */
    public function setFileName($fileName = null)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get mimetype
     *
     * @return string
     */
    public function getMimetype()
    {
        return $this->mimetype;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    public function isImage()
    {
        return in_array($this->mimetype, self::getImageMimetypes());
    }

    public function isSpreadsheet()
    {
        return in_array($this->mimetype, self::getSpreadsheetMimetypes());
    }

    public function isPdf()
    {
        return in_array($this->mimetype, self::getPdfMimetypes());
    }


    public static function getImageMimetypes()
    {
        return [
            'image/jpeg',
            'image/gif',
            'image/png'
        ];
    }

    public static function getSpreadsheetMimetypes()
    {
        return [
            'application/vnd.ms-excel',
            'application/msexcel',
            'application/x-msexcel',
            'application/x-ms-excel',
            'application/x-excel',
            'application/x-dos_ms_excel',
            'application/xls',
            'application/x-xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain', //csv, dif, slk
            'application/x-dbf', // dbf
            'application/xml', //fods, uos, xml
            'text/html', //html
            'application/vnd.oasis.opendocument.spreadsheet', //ods
            'application/vnd.oasis.opendocument.spreadsheet-template', //ots
            'application/octet-stream', // stc, sxc
            'application/vnd.ms-office', //xls. xlt
            'application/zip' //xlsx
        ];
    }

    public static function getPdfMimetypes()
    {
        return [
            'application/pdf',
            'application/acrobat',
            'application/x-pdf',
            'applications/vnd.pdf',
            'text/pdf',
            'text/x-pdf'
        ];
    }
}
