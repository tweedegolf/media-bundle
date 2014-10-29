<?php

namespace TweedeGolf\MediaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use TweedeGolf\MediaBundle\Entity\File;

/**
 * File entity repository class
 * Provides a function the retrieve a filtered and ordered file collection
 */
class FileRepository extends EntityRepository
{

    /**
     * Retrieve a filtered and ordered list of files
     * @param string $type  optional filter for file types
     * @param string $order optional sorting
     * @return array
     */
    public function findSubset($type = 'all', $order = 'newest')
    {
        $qb = $this->createQueryBuilder('f');

        if ($type === 'images') {
            $qb->where($qb->expr()->in('f.mimeType', '?1'));
            $qb->setParameter(1, array_merge(File::getImageMimetypes()));
        } else if ($type === 'documents') {
            $qb->where($qb->expr()->notIn('f.mimeType', '?1'));
            $qb->setParameter(1, array_merge(File::getImageMimetypes()));
        }

        $mapping = FileRepository::getOrderMapping();

        if (in_array($order, array_keys($mapping))) {
            $qb->orderBy($mapping[$order]['field'], $mapping[$order]['direction']);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Get a mapping of sort names to sort parameters
     * @return array 
     */
    static public function getOrderMapping()
    {
        return [
            'oldest'    => ['field' => 'f.createdAt', 'direction' => 'ASC'],
            'newest'    => ['field' => 'f.createdAt', 'direction' => 'DESC'],
            'name-asc'  => ['field' => 'f.fileName',  'direction' => 'ASC'],
            'name-desc' => ['field' => 'f.fileName',  'direction' => 'DESC'],
            'smallest'  => ['field' => 'f.fileSize',  'direction' => 'ASC'],
            'largest'   => ['field' => 'f.fileSize',  'direction' => 'DESC'],
            'type-asc'  => ['field' => 'f.mimeType',  'direction' => 'ASC'],
            'type-desc' => ['field' => 'f.mimeType',  'direction' => 'DESC'],
        ];
    }
}
