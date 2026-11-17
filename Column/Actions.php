<?php
/*
 * This file is part of the Austral List Bundle package.
 *
 * (c) Austral <support@austral.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Austral\ListBundle\Column;

use Austral\ListBundle\Column\Base\ColumnWithPath;

use Austral\Contracts\Entity\EntityInterface;
use Austral\ListBundle\Column\Interfaces\ColumnActionInterface;

/**
 * Austral Column Action.
 * @template T of object
 *
 * @extends ColumnWithPath<T>
 * @implements ColumnActionInterface<T>
 *
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @final
 */
class Actions extends ColumnWithPath implements ColumnActionInterface
{

  /**
   * @var string|null
   */
  protected ?string $picto;

  /**
   * @var string
   */
  protected string $keyname;

  /**
   * @var array<string, ColumnActionInterface<T>>
   */
  protected array $actions = array();

  /**
   * @var EntityInterface<T>|null
   */
  protected ?EntityInterface $object = null;

  /**
   * Choices constructor.
   *
   * @param string $keyname
   * @param string|null $entitled
   * @param string|null $path
   * @param string|null $picto
   * @param array<string, mixed> $options
   */
  public function __construct(string $keyname, ?string $entitled, ?string $path = null, ?string $picto = null, array $options = array())
  {
    parent::__construct("_action.{$keyname}", $entitled, $path, $options);
    $this->keyname = $keyname;
    $this->picto = $picto;
    $this->class .= " link";
  }

  /**
   * @param ColumnActionInterface<T> $action
   *
   * @return $this
   */
  public function addAction(ColumnActionInterface $action): self
  {
    $this->actions[$action->keyname()] = $action;
    return $this;
  }

  /**
   * @param list<ColumnActionInterface<T>> $actions
   *
   * @return $this
   */
  public function addActions(array $actions): self
  {
    foreach($actions as $action)
    {
      $this->addAction($action);
    }
    return $this;
  }

  /**
   * @return array<string, ColumnActionInterface<T>>
   */
  public function actions(): array
  {
    return $this->actions;
  }

  /**
   * Get keyname
   * @return string
   */
  public function keyname(): string
  {
    return $this->keyname;
  }

  /**
   * @param string $keyname
   *
   * @return $this
   */
  public function setKeyname(string $keyname): self
  {
    $this->keyname = $keyname;
    return $this;
  }

  /**
   * Get picto
   * @return string|null
   */
  public function picto(): ?string
  {
    return $this->picto;
  }

  /**
   * @param string|null $picto
   *
   * @return $this
   */
  public function setPicto(?string $picto): self
  {
    $this->picto = $picto;
    return $this;
  }

  /**
   * @param string $translateDomain
   *
   * @return $this
   */
  public function setTranslateDomain(string $translateDomain): self
  {
    parent::setTranslateDomain($translateDomain);
    /** @var Action<T> $action */
    foreach($this->actions() as $action)
    {
      $action->setTranslateDomain($translateDomain);
    }
    return $this;
  }

}