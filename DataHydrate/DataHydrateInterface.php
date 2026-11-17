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
use Austral\ListBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Austral DataHydrate.
 * @template T of object
 *
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @interface
 */
interface DataHydrateInterface
{

  /**
   * @param DataHydrateInterface<T>|null $dataHydrate
   */
  public function __construct(?DataHydrateInterface $dataHydrate = null);

  /**
   * @param string $keyname
   * @param DataHydrateInterface<T>|null $dataHydrate
   * @return DataHydrateInterface<T>
   */
  public function subDataHydrate(string $keyname, ?DataHydrateInterface $dataHydrate = null): DataHydrateInterface;

  /**
   * @return $this
   */
  public function removeSubDataHydrate(): self;

  /**
   * @return EventDispatcherInterface|null
   */
  public function getDispatcher(): ?EventDispatcherInterface;

  /**
   * @param EventDispatcherInterface $dispatcher
   *
   * @return $this
   */
  public function setDispatcher(EventDispatcherInterface $dispatcher): self;

  /**
   * @return $this
   */
  public function dispatchListEvent(): self;

  /**
   * @param string $keyname
   *
   * @return $this
   */
  public function setKeyname(string $keyname): self;

  /**
   * @param FilterInterface<T> $filter
   *
   * @return $this
   */
  public function eventFilter(FilterInterface $filter): self;

  /**
   * @param bool $force
   *
   * @return int
   */
  public function countAll(bool $force = false): int;

  /**
   * @param bool $force
   *
   * @return $this
   */
  public function executePaginator(bool $force = false): self;

  /**
   * @param bool $force
   *
   * @return int
   */
  public function paginatorCount(bool $force = false): int;

  /**
   * @param bool $force
   *
   * @return array<T>
   */
  public function paginatorObjects(bool $force = false): array;

  /**
   * @return int|null
   */
  public function getMaxResult(): ?int;

  /**
   * @param int|null $maxResult
   *
   * @return $this
   */
  public function setMaxResult(?int $maxResult): self;

  /**
   * @return int
   */
  public function getOffset(): int;

  /**
   * @param int $offset
   *
   * @return $this
   */
  public function setOffset(int $offset): self;

  /**
   * @return int
   */
  public function getNumPage(): int;

  /**
   * @param int $numPage
   *
   * @return $this
   */
  public function setNumPage(int $numPage): self;

  /**
   * @return QueryBuilder|null
   */
  public function paginatorQueryBuilder(): ?QueryBuilder;

  /**
   * @return QueryBuilder|null
   */
  public function countAllQueryBuilder(): ?QueryBuilder;

  /**
   * @return EntityManagerORMInterface<T>|null
   */
  public function getEntityManager(): ?EntityManagerORMInterface;

}