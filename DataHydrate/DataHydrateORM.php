<?php
/*
 * This file is part of the Austral List Bundle package.
 *
 * (c) Austral <support@austral.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Austral\ListBundle\DataHydrate;

use Austral\EntityBundle\EntityManager\EntityManagerInterface;
use Austral\ListBundle\Filter\FilterInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;

/**
 * Austral DataHydrate.
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @final
 */
class DataHydrateORM extends DataHydrate
{

  /**
   * @var EntityManagerInterface
   */
  protected EntityManagerInterface $entityManager;

  /**
   * @var QueryBuilder|null
   */
  protected ?QueryBuilder $countAllQueryBuilder = null;

  /**
   * @var QueryBuilder|null
   */
  protected ?QueryBuilder $paginatorQueryBuilder = null;

  /**
   * @var \Closure|null
   */
  protected ?\Closure $queryBuilderClosure = null;

  /**
   * @var \Closure|null
   */
  protected ?\Closure $countAllQueryBuilderClosure = null;

  /**
   * @var \Closure|null
   */
  protected ?\Closure $selectQueryBuilderClosure = null;

  /**
   * @var QueryBuilder|null
   */
  protected ?QueryBuilder $countQueryBuilder = null;

  /**
   * @param DataHydrateORM|null $dataHydrate
   */
  public function __construct(DataHydrateInterface $dataHydrate = null, string $keyname = null)
  {
    parent::__construct($dataHydrate, $keyname);
    if($dataHydrate)
    {
      $this->paginatorQueryBuilder = clone $dataHydrate->paginatorQueryBuilder();
      $this->countAllQueryBuilder = clone $dataHydrate->countAllQueryBuilder();
    }
  }

  /**
   * @param EntityManagerInterface $entityManager
   * @param QueryBuilder|null $queryBuilder
   *
   * @return $this
   */
  public function setEntityManager(EntityManagerInterface $entityManager, ?QueryBuilder $queryBuilder = null): DataHydrateORM
  {
    $this->entityManager = $entityManager;
    $this->countAllQueryBuilder =  $queryBuilder ? clone $queryBuilder : $this->getEntityManager()->createQueryBuilder();
    $this->paginatorQueryBuilder =  $queryBuilder ? clone $queryBuilder : $this->getEntityManager()->createQueryBuilder();
    return $this;
  }

  /**
   * @return EntityManagerInterface
   */
  public function getEntityManager(): EntityManagerInterface
  {
    return $this->dataHydrate ? $this->dataHydrate->getEntityManager() : $this->entityManager;
  }

  /**
   * @param string $keyname
   * @param DataHydrateInterface|null $dataHydrate
   *
   * @return DataHydrateORM
   */
  public function subDataHydrate(string $keyname, ?DataHydrateInterface $dataHydrate = null): DataHydrate
  {
    if(!array_key_exists($keyname, $this->subDataHydrates))
    {
      $subDataHydrate = $dataHydrate ? (clone $dataHydrate)->setKeyname($keyname) : new DataHydrateORM($this, $keyname);
      $subDataHydrate->removeSubDataHydrate();
      $this->subDataHydrates[$keyname] = $subDataHydrate;
    }
    return $this->subDataHydrates[$keyname];
  }

  /**
   * @param QueryBuilder|null $queryBuilder
   * @param \Closure|null $closure
   *
   * @return QueryBuilder
   */
  protected function executeQueryBuilder(QueryBuilder $queryBuilder, \Closure $closure = null): QueryBuilder
  {
    if($closure instanceof \Closure)
    {
      $closure->call($this, $queryBuilder);
    }
    return $queryBuilder;
  }

  /**
   * @param FilterInterface $filter
   *
   * @return DataHydrateORM
   */
  public function eventFilter(FilterInterface $filter): DataHydrateORM
  {
    $this->countAllQueryBuilder = $filter->generateQueryBuilder($this->countAllQueryBuilder(), false);
    $this->paginatorQueryBuilder = $filter->generateQueryBuilder($this->paginatorQueryBuilder());
    return $this;
  }

  /**
   * @param bool $force
   *
   * @return int
   * @throws NoResultException
   * @throws NonUniqueResultException
   */
  public function countAll(bool $force = false): int
  {
    if($force === true || !$this->countAll)
    {
      $this->dispatchListEvent();
      $this->countAll = $this->getEntityManager()->countByQueryBuilder($this->countAllQueryBuilder());
    }
    return $this->countAll;
  }

  /**
   * @param bool $force
   *
   * @return DataHydrateInterface
   * @throws NoResultException
   * @throws NonUniqueResultException
   */
  public function executePaginator(bool $force = false): DataHydrateInterface
  {
    if($force === true || !$this->paginatorObjects || $this->paginatorCount)
    {
      if($this->getMaxResult()) {
        $this->paginatorQueryBuilder()->setMaxResults($this->getMaxResult());
        if($this->getMaxResult() < $this->countAll())
        {
          if($this->getOffset()) {
            $this->paginatorQueryBuilder()->setFirstResult($this->getOffset());
          }
          else if($this->getNumPage()) {
            $numPage = $this->getNumPage() - 1;
            if($this->countAll() <= ($numPage*$this->getMaxResult()))
            {
              $this->setNumPage(0);
              $numPage = 0;
            }
            $this->paginatorQueryBuilder()->setFirstResult($this->getMaxResult() * max($numPage, 0));
          }
        }
      }

      $this->dispatchListEvent();
      $paginatorResult = $this->getEntityManager()->paginatorByQueryBuilder($this->paginatorQueryBuilder());
      $this->paginatorCount = $paginatorResult["count"];
      $this->paginatorObjects = $paginatorResult["objects"];
    }
    return $this;
  }

  /**
   * @return QueryBuilder
   */
  public function countAllQueryBuilder(): QueryBuilder
  {
    return $this->countAllQueryBuilder ?? $this->dataHydrate->countAllQueryBuilder();
  }

  /**
   * @return QueryBuilder
   */
  public function paginatorQueryBuilder(): QueryBuilder
  {
    return $this->paginatorQueryBuilder ?? $this->dataHydrate->paginatorQueryBuilder();
  }


  /**
   * @param \Closure|null $closure
   *
   * @return $this
   */
  public function addQueryBuilderClosure(?\Closure $closure): DataHydrateORM
  {
    $this->countAllQueryBuilder = $this->executeQueryBuilder($this->countAllQueryBuilder, $closure);
    $this->paginatorQueryBuilder = $this->executeQueryBuilder($this->paginatorQueryBuilder, $closure);
    if($this->subDataHydrates)
    {
      /** @var DataHydrateORM $subDataHydrate */
      foreach($this->subDataHydrates as $subDataHydrate)
      {
        $subDataHydrate->addQueryBuilderClosure($closure);
      }
    }
    return $this;
  }

  /**
   * @param \Closure|null $closure
   *
   * @return $this
   */
  public function addQueryBuilderCountAllClosure(?\Closure $closure): DataHydrateORM
  {
    $this->countAllQueryBuilder = $this->executeQueryBuilder($this->countAllQueryBuilder, $closure);
    if($this->subDataHydrates)
    {
      /** @var DataHydrateORM $subDataHydrate */
      foreach($this->subDataHydrates as $subDataHydrate)
      {
        $subDataHydrate->addQueryBuilderCountAllClosure($closure);
      }
    }
    return $this;
  }

  /**
   * @param \Closure|null $closure
   *
   * @return $this
   */
  public function addQueryBuilderCountClosure(?\Closure $closure): DataHydrateORM
  {
    $this->countQueryBuilder = $this->executeQueryBuilder($this->countQueryBuilder, $closure);
    if($this->subDataHydrates)
    {
      /** @var DataHydrateORM $subDataHydrate */
      foreach($this->subDataHydrates as $subDataHydrate)
      {
        $subDataHydrate->addQueryBuilderCountClosure($closure);
      }
    }
    return $this;
  }

  /**
   * @param \Closure|null $closure
   *
   * @return $this
   */
  public function addQueryBuilderPaginatorClosure(?\Closure $closure): DataHydrateORM
  {
    $this->paginatorQueryBuilder = $this->executeQueryBuilder($this->paginatorQueryBuilder, $closure);
    if($this->subDataHydrates)
    {
      /** @var DataHydrateORM $subDataHydrate */
      foreach($this->subDataHydrates as $subDataHydrate)
      {
        $subDataHydrate->addQueryBuilderPaginatorClosure($closure);
      }
    }
    return $this;
  }

}