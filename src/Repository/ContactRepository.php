<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function findByParam(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $persister = $this->_em->getUnitOfWork()->getEntityPersister($this->_entityName);

        return $persister->loadAll($criteria, $orderBy, $limit, $offset);
    }

    public function findInAll($search,$limit=1,$offset=0)
    {

        $checkValidTime= (bool)strtotime($search);
        $date=false;
        if($checkValidTime){
            $date = strtotime($search);

        }

        return $this->createQueryBuilder('c')

            ->where('c.user_name LIKE :search')
            ->orWhere('c.first_name LIKE :search')
            ->orWhere('c.id LIKE :search')
            ->orWhere('c.name_prefix LIKE :search')
            ->orWhere('c.first_name LIKE :search')
            ->orWhere('c.midle_name LIKE :search')
            ->orWhere('c.last_name LIKE :search')
            ->orWhere('c.gender LIKE :search')
            ->orWhere('c.email LIKE :search')
            ->orWhere('c.date_birth = :dateBirth')
            ->orWhere('c.time_birth = :timeBirth')
            ->orWhere('c.age_birth = :ageBirth')
            ->orWhere('c.date_join = :dateJoin')
            ->orWhere('c.age_in_company = :ageCompany')
            ->orWhere('c.phone LIKE :search')
            ->orWhere('c.place LIKE :search')
            ->orWhere('c.country LIKE :search')
            ->orWhere('c.city LIKE :search')
            ->orWhere('c.zip LIKE :search')
            ->orWhere('c.region LIKE :search')
            ->setParameter('search', '%'.$search.'%')
            ->setParameter('dateJoin',  new \DateTime( date('d/M/Y:H:i:s', $date) ?  false : false), \Doctrine\DBAL\Types\Type::DATETIME)
            ->setParameter('ageCompany',  new \DateTime(date('d/M/Y:H:i:s', $date) ?  false : false), \Doctrine\DBAL\Types\Type::DATETIME)
            ->setParameter('ageBirth',  new \DateTime(date('d/M/Y:H:i:s', $date) ?  false : false), \Doctrine\DBAL\Types\Type::DATETIME)
            ->setParameter('timeBirth',  new \DateTime(date('d/M/Y:H:i:s', $date) ?  false : false), \Doctrine\DBAL\Types\Type::DATETIME)
            ->setParameter('dateBirth',  new \DateTime(date('d/M/Y:H:i:s', $date) ?  false : false), \Doctrine\DBAL\Types\Type::DATETIME)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()

            ->getResult();
    }

}
