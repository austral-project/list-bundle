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

use Austral\EntityBundle\EntityManager\EntityManagerORMInterface;
use Austral\EntityBundle\Model\PaginatorResult;
use Austral\ListBundle\Filter\FilterInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;

/**
 * Austral DataHydrate.
 * @template T of object
 *
 * @extends DataHydrate<T>
 * @implements DataHydrateInterface<T>
 *
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @final
 */
class DataHydrateORM extends DataHydrate implements DataHydrateInterface
{

  /**
   * @var EntityManagerORMInterface<T>|null
   */
  protected ?EntityManagerORMInterface $entityManager = null;

  /**
   * @var QueryBuilder|null
   */
  protected ?QueryBuilder $countAllQueryBuilder = null;

  /**
   * @var QueryBuilder|null
   */
  protected ?QueryBuilder $paginatorQueryBuilder = null;

  /**
   * @var \Closure(object|null): mixed|null
   */
  protected ?\Closure $queryBuilderClosure = null;

  /**
   * @var \Closure(object|null): mixed|null
   */
  protected ?\Closure $countAllQueryBuilderClosure = null;

  /**
   * @var \Closure(object|null): mixed|null
   */
  protected ?\Closure $selectQueryBuilderClosure = null;

  /**
   * @var QueryBuilder|null
   */
  protected ?QueryBuilder $countQueryBuilder = null;

  /**
   * @param DataHydrateInterface<T>|null $dataHydrate
   */
  public function __construct(?DataHydrateInterface $dataHydrate = null, ?string $keyname = null)
  {
    parent::__construct($dataHydrate, $keyname);
    if($dataHydrate)
    {
      $this->paginatorQueryBuilder = $dataHydrate->paginatorQueryBuilder() ? clone $dataHydrate->paginatorQueryBuilder() : null;
      $this->countAllQueryBuilder = $dataHydrate->countAllQueryBuilder() ? clone $dataHydrate->countAllQueryBuilder() : null;
    }
  }

  /**
   * @param EntityManagerORMInterface<T> $entityManager
   * @param QueryBuilder|null $queryBuilder
   *
   * @return $this
   */
  public function setEntityManager(EntityManagerORMInterface $entityManager, ?QueryBuilder $queryBuilder = null): DataHydrateORM
  {
    $this->entityManager = $entityManager;
    $this->countAllQueryBuilder =  $queryBuilder ? clone $queryBuilder : $this->getEntityManager()?->createQueryBuilder();
    $this->paginatorQueryBuilder =  $queryBuilder ? clone $queryBuilder : $this->getEntityManager()?->createQueryBuilder();
    return $this;
  }

  /**
   * @return EntityManagerORMInterface<T>|null
   */
  public function getEntityManager(): ?EntityManagerORMInterface
  {
    return $this->dataHydrate ? $this->dataHydrate->getEntityManager() : $this->entityManager;
  }

  /**
   * @param string $keyname
   * @param DataHydrateInterface<T>|null $dataHydrate
   *
   * @return DataHydrateInterface<T>
   */
  public function subDataHydrate(string $keyname, ?DataHydrateInterface $dataHydrate = null): DataHydrateInterface
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
   * @param QueryBuilder $queryBuilder
   * @param \Closure(object|null): mixed|null $closure
   *
   * @return QueryBuilder
   */
  protected function executeQueryBuilder(QueryBuilder $queryBuilder, ?\Closure $closure = null): QueryBuilder
  {
    if($closure instanceof \Closure)
    {
      $closure->call($this, $queryBuilder);
    }
    return $queryBuilder;
  }

  /**
   * @param FilterInterface<T> $filter
   *
   * @return $this
   */
  public function eventFilter(FilterInterface $filter): self
  {
    $this->countAllQueryBuilder = $this->countAllQueryBuilder() ? $filter->generateQueryBuilder($this->countAllQueryBuilder(), false) : null;
    $this->paginatorQueryBuilder = $this->paginatorQueryBuilder() ? $filter->generateQueryBuilder($this->paginatorQueryBuilder()) : null;
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
      $countAll = $this->countAllQueryBuilder() ? $this->getEntityManager()?->countByQueryBuilder($this->countAllQueryBuilder()) : 0;
      $this->countAll = $countAll ?? 0;
    }
    return $this->countAll;
  }

  /**
   * @param bool $force
   *
   * @return $this
   * @throws NoResultException
   * @throws NonUniqueResultException
   */
  public function executePaginator(bool $force = false): self
  {
    if($force === true || !$this->paginatorObjects || !$this->paginatorCount)
    {
      if($this->getMaxResult()) {
        $this->paginatorQueryBuilder()?->setMaxResults($this->getMaxResult());
        if($this->getMaxResult() < $this->countAll())
        {
          if($this->getOffset()) {
            $this->paginatorQueryBuilder()?->setFirstResult($this->getOffset());
          }
          else if($this->getNumPage()) {
            $numPage = $this->getNumPage() - 1;
            if($this->countAll() <= ($numPage*$this->getMaxResult()))
            {
              $this->setNumPage(0);
              $numPage = 0;
            }
            $this->paginatorQueryBuilder()?->setFirstResult($this->getMaxResult() * max($numPage, 0));
          }
        }
      }

      $this->dispatchListEvent();
      if($this->getEntityManager() && $this->paginatorQueryBuilder())
      {
        $paginatorResult = $this->getEntityManager()->paginatorByQueryBuilder($this->paginatorQueryBuilder());
      }
      else
      {
        $paginatorResult = new PaginatorResult();
      }
      $this->paginatorCount = $paginatorResult->getCount();
      $this->paginatorObjects = $paginatorResult->getObjects();
    }
    return $this;
  }

  /**
   * @return QueryBuilder|null
   */
  public function countAllQueryBuilder(): ?QueryBuilder
  {
    return $this->countAllQueryBuilder ?? $this->dataHydrate?->countAllQueryBuilder();
  }

  /**
   * @return QueryBuilder|null
   */
  public function paginatorQueryBuilder(): ?QueryBuilder
  {
    return $this->paginatorQueryBuilder ?? $this->dataHydrate?->paginatorQueryBuilder();
  }


  /**
   * @param \Closure(object|null): mixed|null $closure
   *
   * @return $this
   */
  public function addQueryBuilderClosure(?\Closure $closure): self
  {
    $this->countAllQueryBuilder = $this->countAllQueryBuilder ? $this->executeQueryBuilder($this->countAllQueryBuilder, $closure) : null;
    $this->paginatorQueryBuilder = $this->paginatorQueryBuilder ? $this->executeQueryBuilder($this->paginatorQueryBuilder, $closure) : null;
    if($this->subDataHydrates)
    {
      /** @var DataHydrateORM<T> $subDataHydrate */
      foreach($this->subDataHydrates as $subDataHydrate)
      {
        $subDataHydrate->addQueryBuilderClosure($closure);
      }
    }
    return $this;
  }

  /**
   * @param \Closure(object|null): mixed|null $closure
   *
   * @return $this
   */
  public function addQueryBuilderCountAllClosure(?\Closure $closure): self
  {
    $this->countAllQueryBuilder = $this->countAllQueryBuilder ? $this->executeQueryBuilder($this->countAllQueryBuilder, $closure) : null;
    if($this->subDataHydrates)
    {
      /** @var DataHydrateORM<T> $subDataHydrate */
      foreach($this->subDataHydrates as $subDataHydrate)
      {
        $subDataHydrate->addQueryBuilderCountAllClosure($closure);
      }
    }
    return $this;
  }

  /**
   * @param \Closure(object|null): mixed|null $closure
   *
   * @return $this
   */
  public function addQueryBuilderCountClosure(?\Closure $closure): self
  {
    $this->countQueryBuilder = $this->countQueryBuilder ? $this->executeQueryBuilder($this->countQueryBuilder, $closure) : null;
    if($this->subDataHydrates)
    {
      /** @var DataHydrateORM<T> $subDataHydrate */
      foreach($this->subDataHydrates as $subDataHydrate)
      {
        $subDataHydrate->addQueryBuilderCountClosure($closure);
      }
    }
    return $this;
  }

  /**
   * @param \Closure(object|null): mixed|null $closure
   *
   * @return $this
   */
  public function addQueryBuilderPaginatorClosure(?\Closure $closure): self
  {
    $this->paginatorQueryBuilder = $this->paginatorQueryBuilder ? $this->executeQueryBuilder($this->paginatorQueryBuilder, $closure) : null;
    if($this->subDataHydrates)
    {
      /** @var DataHydrateORM<T> $subDataHydrate */
      foreach($this->subDataHydrates as $subDataHydrate)
      {
        $subDataHydrate->addQueryBuilderPaginatorClosure($closure);
      }
    }
    return $this;
  }

}