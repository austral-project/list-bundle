<?php
/*
 * This file is part of the Austral List Bundle package.
 *
 * (c) Austral <support@austral.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Austral\ListBundle\Event;

use Austral\ListBundle\DataHydrate\DataHydrateInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Austral Form Event.
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @final
 */
class ListEvent extends Event
{
  const EVENT_AUSTRAL_LIST_FILTER = "austral.event.list.filter";

  /**
   * @var string
   */
  protected string $keyname;

  /**
   * @var DataHydrateInterface
   */
  protected DataHydrateInterface $dataHydrate;

  /**
   * FormEvent constructor.
   *
   * @param string $keyname
   * @param DataHydrateInterface $dataHydrate
   */
  public function __construct(string $keyname, DataHydrateInterface $dataHydrate)
  {
    $this->keyname = $keyname;
    $this->dataHydrate = $dataHydrate;
  }

  /**
   * @return DataHydrateInterface
   */
  public function getDataHydrate(): DataHydrateInterface
  {
    return $this->dataHydrate;
  }

  /**
   * @param DataHydrateInterface $dataHydrate
   *
   * @return $this
   */
  public function setDataHydrate(DataHydrateInterface $dataHydrate): ListEvent
  {
    $this->dataHydrate = $dataHydrate;
    return $this;
  }

  /**
   * @return string
   */
  public function getKeyname(): string
  {
    return $this->keyname;
  }

  /**
   * @param string $keyname
   *
   * @return $this
   */
  public function setKeyname(string $keyname): ListEvent
  {
    $this->keyname = $keyname;
    return $this;
  }

}