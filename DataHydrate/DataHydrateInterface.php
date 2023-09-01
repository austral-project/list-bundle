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

use Austral\ListBundle\Filter\FilterInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Austral DataHydrate.
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @implements
 */
interface DataHydrateInterface
{

  /**
   * @param DataHydrateInterface|null $dataHydrate
   */
  public function __construct(DataHydrateInterface $dataHydrate = null);

  /**
   * @param string $keyname
   * @param DataHydrateInterface|null $dataHydrate
   * @return DataHydrateInterface
   */
  public function subDataHydrate(string $keyname, ?DataHydrateInterface $dataHydrate = null): DataHydrateInterface;

  /**
   * @return EventDispatcherInterface|null
   */
  public function getDispatcher(): ?EventDispatcherInterface;

  /**
   * @param EventDispatcherInterface $dispatcher
   *
   * @return $this
   */
  public function setDispatcher(EventDispatcherInterface $dispatcher): DataHydrateInterface;

  /**
   * @return DataHydrateInterface
   */
  public function dispatchListEvent(): DataHydrateInterface;

  /**
   * @param FilterInterface $filter
   *
   * @return DataHydrateInterface
   */
  public function eventFilter(FilterInterface $filter): DataHydrateInterface;

  /**
   * @param bool $force
   *
   * @return int
   */
  public function countAll(bool $force = false): int;

  /**
   * @param bool $force
   *
   * @return DataHydrateInterface
   */
  public function executePaginator(bool $force = false): DataHydrateInterface;

  /**
   * @param bool $force
   *
   * @return int
   */
  public function paginatorCount(bool $force = false): int;

  /**
   * @param bool $force
   *
   * @return array
   */
  public function paginatorObjects(bool $force = false): array;

  /**
   * @return int|null
   */
  public function getMaxResult(): ?int;

  /**
   * @param int|null $maxResult
   *
   * @return DataHydrateInterface
   */
  public function setMaxResult(?int $maxResult): DataHydrateInterface;

  /**
   * @return int
   */
  public function getOffset(): int;

  /**
   * @param int $offset
   *
   * @return DataHydrateInterface
   */
  public function setOffset(int $offset): DataHydrateInterface;

  /**
   * @return int
   */
  public function getNumPage(): int;

  /**
   * @param int $numPage
   *
   * @return DataHydrateInterface
   */
  public function setNumPage(int $numPage): DataHydrateInterface;

}