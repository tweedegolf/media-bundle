<?php

namespace TweedeGolf\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\File as UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Attachment
 *
 * @ORM\Entity
 * @ORM\Table()
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass="TweedeGolf\MediaBundle\Entity\FileRepository")
 */
class File
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
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Vich\UploadableField(mapping="media_file", fileNameProperty="fileName")
     *
     * @var UploadedFile $file
     */
    private $file;

    /**
     * @var string $fileName
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $fileName;

    /**
     * @var string $fileSize
     * @ORM\Column(type="integer", nullable=false)
     */
    private $fileSize;

    /**
     * @var string $mimeType
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $mimeType;

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
     * @param UploadedFile $file
     * @return $this
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        $this->setUpdatedAt(new \DateTime());

        if ($file) {
            $this->mimeType = $file->getMimeType();
            $this->fileSize = $file->getSize();
        } else {
            $this->mimeType = null;
            $this->fileSize = null;
        }

        return $this;
    }

    /**
     * Get file
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     * @return UploadedFile
     */
    public function setFileName($fileName = null)
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getExtension()
    {
        if (!$this->fileName) {

            return null;
        }

        return pathinfo($this->fileName, PATHINFO_EXTENSION);
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
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
        return in_array($this->mimeType, self::getImageMimetypes());
    }

    public function isSpreadsheet()
    {
        return in_array($this->mimeType, self::getSpreadsheetMimetypes());
    }

    public function isPdf()
    {
        return in_array($this->mimeType, self::getPdfMimetypes());
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
