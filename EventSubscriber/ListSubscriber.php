<?php
/*
 * This file is part of the Austral List Bundle package.
 *
 * (c) Austral <support@austral.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Austral\ListBundle\EventSubscriber;

use Austral\ListBundle\Event\ListEvent;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Austral ListSubscriber.
 * @template T of object
 *
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @final
 */
class ListSubscriber implements EventSubscriberInterface
{
  /**
   * @return array<string, string|array{0: string, 1: int}|list<array{0: string, 1?: int}>>
   */
  public static function getSubscribedEvents(): array
  {
    return [
      ListEvent::EVENT_AUSTRAL_LIST_FILTER              =>  ["filter", 0],
    ];
  }

  /**
   * @param ListEvent<T> $listEvent
   */
  public function filter(ListEvent $listEvent): void
  {

  }

}