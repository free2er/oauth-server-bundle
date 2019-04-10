<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Repository;

use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\ORMException;
use Free2er\OAuth\Entity\AuthorizationCode;

/**
 * Репозиторий кодов авторизации
 *
 * @method AuthorizationCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthorizationCode|null findOneBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method AuthorizationCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method AuthorizationCode[]    findAll()
 */
class AuthorizationCodeRepository extends ServiceEntityRepository implements AuthorizationCodeRepositoryInterface
{
    /**
     * Конструктор
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuthorizationCode::class);
    }

    /**
     * Возвращает просроченные коды авторизации
     *
     * @param DateTimeInterface $time
     *
     * @return AuthorizationCode[]
     */
    public function getExpired(DateTimeInterface $time): array
    {
        $qb = $this->createQueryBuilder('c');
        $qb->where($qb->expr()->lte('c.expiredAt', ':time'));
        $qb->setParameter('time', $time);

        return $qb->getQuery()->getResult();
    }

    /**
     * Возвращает код авторизации
     *
     * @param string $id
     *
     * @return AuthorizationCode|null
     */
    public function getCode(string $id): ?AuthorizationCode
    {
        return $this->find($id);
    }

    /**
     * Сохраняет код авторизации
     *
     * @param AuthorizationCode $code
     *
     * @throws ORMException
     */
    public function save(AuthorizationCode $code): void
    {
        $em = $this->getEntityManager();
        $em->persist($code);
        $em->flush($code);
    }

    /**
     * Удаляет код авторизации
     *
     * @param AuthorizationCode $code
     *
     * @throws ORMException
     */
    public function remove(AuthorizationCode $code): void
    {
        $em = $this->getEntityManager();
        $em->remove($code);
        $em->flush($code);
    }
}
