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

use Austral\ListBundle\Event\ListEvent;
use Austral\ListBundle\Filter\FilterInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Austral DataHydrate.
 * @template T of object
 *
 * @implements DataHydrateInterface<T>
 *
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @abstract
 */
abstract class DataHydrate implements DataHydrateInterface
{

  /**
   * @var EventDispatcherInterface|null
   */
  protected ?EventDispatcherInterface $dispatcher = null;

  /**
   * @var DataHydrateInterface<T>|null
   */
  protected ?DataHydrateInterface $dataHydrate = null;

  /**
   * @var string|null
   */
  protected ?string $keyname = null;

  /**
   * @var array<string, DataHydrateInterface<T>>
   */
  protected array $subDataHydrates = array();

  /**
   * @var int|null
   */
  protected ?int $maxResult = null;

  /**
   * @var int
   */
  protected int $offset = 0;

  /**
   * @var int
   */
  protected int $numPage = 1;

  /**
   * @var int
   */
  protected int $countAll = 0;

  /**
   * @var int
   */
  protected int $paginatorCount = 0;

  /**
   * @var array<int|string, object>
   */
  protected array $paginatorObjects = array();

  /**
   * @var bool
   */
  protected bool $dispatchListEvent = false;

  /**
   * @param DataHydrateInterface<T>|null $dataHydrate
   */
  public function __construct(?DataHydrateInterface $dataHydrate = null, ?string $keyname = null)
  {
    $this->dataHydrate = $dataHydrate;
    $this->keyname = $keyname;
    $this->subDataHydrates = array();
  }

  /**
   * @inheritDoc
   */
  public function setKeyname(string $keyname): self
  {
    $this->keyname = $keyname;
    return $this;
  }

  /**
   * @return EventDispatcherInterface|null
   */
  public function getDispatcher(): ?EventDispatcherInterface
  {
    return $this->dispatcher;
  }

  /**
   * @param EventDispatcherInterface $dispatcher
   *
   * @return $this
   */
  public function setDispatcher(EventDispatcherInterface $dispatcher): self
  {
    $this->dispatcher = $dispatcher;
    return $this;
  }

  /**
   * @return $this
   */
  public function dispatchListEvent(): self
  {
    if($this->dispatchListEvent === false)
    {
      if($this->dataHydrate && $this->keyname)
      {
        $listEvent = new ListEvent($this->keyname, $this);
        $this->dataHydrate->getDispatcher()?->dispatch($listEvent, ListEvent::EVENT_AUSTRAL_LIST_FILTER);
      }
      elseif($this->dispatcher)
      {
        if($this->subDataHydrates)
        {
          /** @var DataHydrate<T> $subDataCollector */
          foreach($this->subDataHydrates as $keyname => $subDataCollector)
          {
            $listEvent = new ListEvent($keyname, $this);
            $this->dispatcher->dispatch($listEvent, ListEvent::EVENT_AUSTRAL_LIST_FILTER);
          }
        }
      }
      $this->dispatchListEvent = true;
    }
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function removeSubDataHydrate(): self
  {
    $this->subDataHydrates = array();
    return $this;
  }

  /**
   * @param FilterInterface<T> $filter
   *
   * @return $this
   */
  abstract public function eventFilter(FilterInterface $filter): self;

  /**
   * @param string $keyname
   * @param DataHydrateInterface<T>|null $dataHydrate
   * @return DataHydrateInterface<T>
   */
  abstract public function subDataHydrate(string $keyname, ?DataHydrateInterface $dataHydrate = null): DataHydrateInterface;

  /**
   * @param bool $force
   *
   * @return int
   */
  abstract public function countAll(bool $force = false): int;

  /**
   * @param bool $force
   *
   * @return $this
   */
  abstract public function executePaginator(bool $force = false): self;

  /**
   * @param bool $force
   *
   * @return int
   */
  public function paginatorCount(bool $force = false): int
  {
    $this->executePaginator($force);
    return $this->paginatorCount;
  }

  /**
   * @param bool $force
   *
   * @return array<int|string, object>
   */
  public function paginatorObjects(bool $force = false): array
  {
    $this->executePaginator($force);
    return $this->paginatorObjects;
  }

  /**
   * @return int|null
   */
  public function getMaxResult(): ?int
  {
    return $this->maxResult;
  }

  /**
   * @param int|null $maxResult
   *
   * @return $this
   */
  public function setMaxResult(?int $maxResult): self
  {
    $this->maxResult = $maxResult;
    return $this;
  }

  /**
   * @return int
   */
  public function getOffset(): int
  {
    return $this->offset;
  }

  /**
   * @param int $offset
   *
   * @return $this
   */
  public function setOffset(int $offset): self
  {
    $this->offset = $offset;
    return $this;
  }

  /**
   * @return int
   */
  public function getNumPage(): int
  {
    return $this->numPage;
  }

  /**
   * @param int $numPage
   *
   * @return $this
   */
  public function setNumPage(int $numPage): self
  {
    $this->numPage = $numPage;
    return $this;
  }

}