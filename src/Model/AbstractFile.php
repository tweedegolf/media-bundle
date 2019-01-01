<?php

namespace TweedeGolf\MediaBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File as UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\MappedSuperclass
 * @Vich\Uploadable
 */
abstract class AbstractFile
{
    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @Assert\File(maxSize="20M", mimeTypes={
     *     "image/png",
     *     "image/jpg",
     *     "image/jpeg",
     *     "image/gif",
     *     "text/plain",
     *     "text/richtext",
     *     "audio/mpeg",
     *     "audio/mp3",
     *     "application/pdf",
     *     "application/x-pdf",
     *     "application/msword",
     *     "application/vnd.ms-excel",
     *     "application/vnd.ms-powerpoint",
     *     "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
     *     "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
     *     "application/vnd.openxmlformats-officedocument.presentationml.presentation"
     * })
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Vich\UploadableField(mapping="tgmedia_file", fileNameProperty="fileName")
     *
     * @var UploadedFile|null
     */
    protected $file;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $fileName;

    /**
     * @var string|null
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $fileSize;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $mimeType;

    public function __toString(): string
    {
        return (string) $this->fileName;
    }

    public function setFile(?UploadedFile $file = null): self
    {
        $this->file = $file;

        $this->updatedAt = new \DateTime();

        if ($file) {
            $this->mimeType = $file->getMimeType();
            $this->fileSize = $file->getSize();
        } else {
            $this->mimeType = null;
            $this->fileSize = null;
        }

        return $this;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFileName(?string $fileName = null): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getExtension(): ?string
    {
        if (!$this->fileName) {
            return null;
        }

        return \pathinfo($this->fileName, PATHINFO_EXTENSION);
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    /**
     * Check if the file is an image based on its mime type.
     *
     * @return bool
     */
    public function isImage(): bool
    {
        return \in_array($this->mimeType, self::getImageMimetypes());
    }

    /**
     * Return valid image mime types.
     *
     * @return array|string[]
     */
    public static function getImageMimetypes(): array
    {
        return [
            'image/jpg',
            'image/jpeg',
            'image/gif',
            'image/png',
        ];
    }

    public function setMimeType(?string $type): self
    {
        $this->mimeType = $type;

        return $this;
    }

    public function setFileSize(?int $size): self
    {
        $this->fileSize = $size;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }
}
