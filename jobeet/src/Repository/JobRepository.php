<?php

namespace App\Repository;

use App\Entity\Job;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;
use App\Entity\Category;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;

class JobRepository extends EntityRepository
{
    /**
     * @param Category $category
     * 
     * @return AbstractQuery
     */
    public function getPaginatedActiveJobsByCategoryQuery(Category $category) : AbstractQuery{
        return $this->createQueryBuilder('j')
        ->where('j.category = :category')
        ->andWhere('j.expiresAt > :date')
        ->andWhere('j.activated = :activated')
        ->setParameter('category', $category)
        ->setParameter('date', new \Datetime())
        ->setParameter('activated', true)
        ->getQuery();
    }
   /* *
     * @param $id
     * 
     * @return Job|null
     */
    public function findActiveJobs(int $id) : ?Job
    {
        return $this->createQueryBuilder('j')
            ->select('j')
            ->where('j.id = :id')  
            ->andWhere('j.expiresAt > :date')
            ->setParameter('id', $id)
            ->setParameter('date', new \DateTime())
            ->getQuery()
            ->getOneOrNullResult();
    }
        /* $qb = $this->createQueryBuilder('j')
            ->where('j.expiresAt > :date')
            ->setParameter('date', new \DateTime())
            ->orderBy('j.expiresAt','DESC');

        if($categoryId){
            $qb->andWhere('j.category = :categoryId')
                ->setParameter('categoryId',$categoryId);
        } 

        return $qb->getQuery()->getResult();
        */
}

?>