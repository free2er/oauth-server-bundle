<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Repository;

use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\ORMException;
use Free2er\OAuth\Entity\RefreshToken;

/**
 * Репозиторий ключей продления доступа
 *
 * @method RefreshToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefreshToken|null findOneBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method RefreshToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method RefreshToken[]    findAll()
 */
class RefreshTokenRepository extends ServiceEntityRepository implements RefreshTokenRepositoryInterface
{
    /**
     * Конструктор
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefreshToken::class);
    }

    /**
     * Возвращает просроченные ключи продления доступа
     *
     * @param DateTimeInterface $time
     *
     * @return RefreshToken[]
     */
    public function getExpired(DateTimeInterface $time): array
    {
        $qb = $this->createQueryBuilder('t');
        $qb->where($qb->expr()->lte('t.expiredAt', ':time'));
        $qb->setParameter('time', $time);

        return $qb->getQuery()->getResult();
    }

    /**
     * Возвращает ключ продления доступа
     *
     * @param string $id
     *
     * @return RefreshToken|null
     */
    public function getToken(string $id): ?RefreshToken
    {
        return $this->find($id);
    }

    /**
     * Сохраняет ключ продления доступа
     *
     * @param RefreshToken $token
     *
     * @throws ORMException
     */
    public function save(RefreshToken $token): void
    {
        $em = $this->getEntityManager();
        $em->persist($token);
        $em->flush($token);
    }

    /**
     * Удаляет ключ продления доступа
     *
     * @param RefreshToken $token
     *
     * @throws ORMException
     */
    public function remove(RefreshToken $token): void
    {
        $em = $this->getEntityManager();
        $em->remove($token);
        $em->flush($token);
    }
}
