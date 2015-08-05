<?php

/*
 * This file is part of the ApoutchikaMediaBundle package.
 *
 * @author Julien Philippon <juphilippon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apoutchika\MediaBundle\Manager;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

abstract class BaseManager
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var string
     */
    protected $class;

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->entityManager->getRepository($this->class);
    }

    /**
     * @return object
     */
    public function create()
    {
        return new $this->class();
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @param array      $where
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return array
     */
    public function findBy(array $where, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->getRepository()->findBy($where, $orderBy, $limit, $offset);
    }

    /**
     * @param array      $where
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return object
     */
    public function findOneBy(array $where)
    {
        return $this->getRepository()->findOneBy($where);
    }

    /**
     * @param int $id
     *
     * return object|null
     */
    public function find($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * @param object $entity
     * @param bool   $flush
     */
    public function delete($entity, $flush = true)
    {
        $this->entityManager->remove($entity);

        if ($flush == true) {
            $this->entityManager->flush();
        }
    }

    /**
     * @param object $entity
     * @param bool   $flush
     */
    public function save($entity, $flush = true)
    {
        $this->entityManager->persist($entity);

        if ($flush == true) {
            $this->entityManager->flush();
        }
    }
}
