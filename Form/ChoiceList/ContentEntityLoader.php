<?php

namespace AGB\Bundle\ContentBundle\Form\ChoiceList;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;

use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContentEntityLoader implements EntityLoaderInterface
{
    private $em;
    private $contentController;
    private $basedOnNode;

    public function __construct(Controller $c, EntityManager $em, $node = null)
    {
        $this->em = $em;
        $this->categoryController = $c;
        $this->basedOnNode = $node;
    }

    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * {@inheritDoc}
     */
    public function getEntities()
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('c')
            ->from('AGBContentBundle:Content', 'c')
        ;

        if (!is_null($this->basedOnNode)) {
            $qb->where($qb->expr()->notIn(
                'c.id',
                $this->getEntityManager()
                    ->createQueryBuilder()
                    ->select('n')
                    ->from('AGBContentBundle:Content', 'n')
                    ->where('n.root = '.$this->basedOnNode->getRoot())
                    ->andWhere($qb->expr()->between(
                        'n.lft',
                        $this->basedOnNode->getLeft(),
                        $this->basedOnNode->getRight()
                    ))
                    ->getDQL()
            ));
        }

        $q = $qb->getQuery();

        return $q->getResult();
    }

    /**
     * {@inheritDoc}
     */
    public function getEntitiesByIds($identifier, array $values)
    {
        $q = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('c')
            ->from('AGBContentBundle:Content', 'c')
            ->where($qb->expr()->in(
                'c.'.$identifier,
                ':ids'
            ))
            ->setParameter('ids', $values, Connection::PARAM_INT_ARRAY)
            ->getQuery()
        ;

        return $q->getResult();
    }
}
