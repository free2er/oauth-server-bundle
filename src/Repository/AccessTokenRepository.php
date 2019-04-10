<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Repository;

use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\ORMException;
use Free2er\OAuth\Entity\AccessToken;

/**
 * Репозиторий ключей доступа
 *
 * @method AccessToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessToken|null findOneBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method AccessToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method AccessToken[]    findAll()
 */
class AccessTokenRepository extends ServiceEntityRepository implements AccessTokenRepositoryInterface
{
    /**
     * Конструктор
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessToken::class);
    }

    /**
     * Возвращает просроченные ключи доступа
     *
     * @param DateTimeInterface $time
     *
     * @return AccessToken[]
     */
    public function getExpired(DateTimeInterface $time): array
    {
        $qb = $this->createQueryBuilder('t');
        $qb->where($qb->expr()->lte('t.expiredAt', ':time'));
        $qb->setParameter('time', $time);

        return $qb->getQuery()->getResult();
    }

    /**
     * Возвращает ключ доступа
     *
     * @param string $id
     *
     * @return AccessToken|null
     */
    public function getToken(string $id): ?AccessToken
    {
        return $this->find($id);
    }

    /**
     * Сохраняет ключ доступа
     *
     * @param AccessToken $token
     *
     * @throws ORMException
     */
    public function save(AccessToken $token): void
    {
        $em = $this->getEntityManager();
        $em->persist($token);
        $em->flush($token);
    }

    /**
     * Удаляет ключ доступа
     *
     * @param AccessToken $token
     *
     * @throws ORMException
     */
    public function remove(AccessToken $token): void
    {
        $em = $this->getEntityManager();
        $em->remove($token);
        $em->flush($token);
    }
}
