<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\ORMException;
use Free2er\OAuth\Entity\Client;

/**
 * Репозиторий клиентов
 *
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Client[]    findAll()
 */
class ClientRepository extends ServiceEntityRepository implements ClientRepositoryInterface
{
    /**
     * Конструктор
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    /**
     * Возвращает клиента
     *
     * @param string $id
     *
     * @return Client|null
     */
    public function getClient(string $id): ?Client
    {
        return $this->find($id);
    }

    /**
     * Сохраняет клиента
     *
     * @param Client $client
     *
     * @throws ORMException
     */
    public function save(Client $client): void
    {
        $em = $this->getEntityManager();
        $em->persist($client);
        $em->flush($client);
    }

    /**
     * Удаляет клиента
     *
     * @param Client $client
     *
     * @throws ORMException
     */
    public function remove(Client $client): void
    {
        $em = $this->getEntityManager();
        $em->remove($client);
        $em->flush($client);
    }
}
