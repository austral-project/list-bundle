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
   * @var DataHydrate|null
   */
  protected ?DataHydrate $dataHydrate = null;

  /**
   * @var string|null
   */
  protected ?string $keyname = null;

  /**
   * @var array
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
   * @var array
   */
  protected array $paginatorObjects = array();

  /**
   * @var bool
   */
  protected bool $dispatchListEvent = false;

  /**
   * @param DataHydrateInterface|null $dataHydrate
   */
  public function __construct(DataHydrateInterface $dataHydrate = null, string $keyname = null)
  {
    $this->dataHydrate = $dataHydrate;
    $this->keyname = $keyname;
    $this->subDataHydrates = array();
  }

  /**
   * @param string|null $keyname
   *
   * @return DataHydrate
   */
  public function setKeyname(?string $keyname): DataHydrate
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
  public function setDispatcher(EventDispatcherInterface $dispatcher): DataHydrate
  {
    $this->dispatcher = $dispatcher;
    return $this;
  }

  /**
   * @return DataHydrate
   */
  public function dispatchListEvent(): DataHydrate
  {
    if($this->dispatchListEvent === false)
    {
      if($this->dataHydrate)
      {
        $listEvent = new ListEvent($this->keyname, $this);
        $this->dataHydrate->getDispatcher()->dispatch($listEvent, ListEvent::EVENT_AUSTRAL_LIST_FILTER);
      }
      elseif($this->dispatcher)
      {
        if($this->subDataHydrates)
        {
          /** @var DataHydrate $subDataCollector */
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
   * @return $this
   */
  public function removeSubDataHydrate(): DataHydrate
  {
    $this->subDataHydrates = array();
    return $this;
  }


  /**
   * @param FilterInterface $filter
   *
   * @return DataHydrate
   */
  abstract public function eventFilter(FilterInterface $filter): DataHydrate;

  /**
   * @param string $keyname
   * @param DataHydrateInterface|null $dataHydrate
   * @return DataHydrate
   */
  abstract public function subDataHydrate(string $keyname, ?DataHydrateInterface $dataHydrate = null): DataHydrate;

  /**
   * @param bool $force
   *
   * @return int
   */
  abstract public function countAll(bool $force = false): int;

  /**
   * @param bool $force
   *
   * @return DataHydrateInterface
   */
  abstract public function executePaginator(bool $force = false): DataHydrateInterface;

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
   * @return array
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
   * @return DataHydrateInterface
   */
  public function setMaxResult(?int $maxResult): DataHydrateInterface
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
   * @return DataHydrateInterface
   */
  public function setOffset(int $offset): DataHydrateInterface
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
   * @return DataHydrateInterface
   */
  public function setNumPage(int $numPage): DataHydrateInterface
  {
    $this->numPage = $numPage;
    return $this;
  }

}