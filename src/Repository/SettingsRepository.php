<?php
declare(strict_types=1);

namespace Vim\Settings\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Vim\Settings\Entity\AbstractSettings;

/**
 * @method AbstractSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method AbstractSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method AbstractSettings[]    findAll()
 * @method AbstractSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbstractSettings::class);
    }

    public function findOneByCode(string $code): ?AbstractSettings
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
