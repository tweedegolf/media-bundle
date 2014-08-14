<?php

namespace TweedeGolf\MediaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use TweedeGolf\MediaBundle\Entity\File;

class FileRepository extends EntityRepository
{
    /**
     * Creates a query builder returning documents having image files.
     * @return QueryBuilder
     */
    public function createImagesBuilder()
    {
        $qb = $this->createQueryBuilder('f');
        $qb->where($qb->expr()->in('f.mimeType', '?1'));
        $qb->setParameter(1, array_merge(File::getImageMimetypes()));

        return $qb;
    }

    /**
     * Returns the documents for which the type is an image.
     * @return File[]
     */
    public function findImages()
    {
        return $this->createImagesBuilder()->getQuery()->getResult();
    }
}
