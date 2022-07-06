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

use Austral\EntityBundle\Entity\EntityInterface;
use Austral\ListBundle\Column\Interfaces\ColumnActionInterface;

/**
 * Austral Column Action.
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
   * @var array
   */
  protected array $actions = array();

  /**
   * @var EntityInterface|null
   */
  protected ?EntityInterface $object = null;

  /**
   * Choices constructor.
   *
   * @param string $keyname
   * @param string $entitled
   * @param string|null $path
   * @param string|null $picto
   * @param array $options
   */
  public function __construct(string $keyname, string $entitled, string $path = null, string $picto = null, array $options = array())
  {
    parent::__construct("_action.{$keyname}", $entitled, $path, $options);
    $this->keyname = $keyname;
    $this->picto = $picto;
    $this->class .= " link";
  }

  /**
   * @param ColumnActionInterface $action
   *
   * @return $this
   */
  public function addAction(ColumnActionInterface $action): Actions
  {
    $this->actions[$action->keyname()] = $action;
    return $this;
  }

  /**
   * @return array
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
   * @return Actions
   */
  public function setKeyname(string $keyname): Actions
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
   * @return Actions
   */
  public function setPicto(?string $picto): Actions
  {
    $this->picto = $picto;
    return $this;
  }

  /**
   * @param string $translateDomain
   *
   * @return $this
   */
  public function setTranslateDomain(string $translateDomain): ColumnWithPath
  {
    parent::setTranslateDomain($translateDomain);
    /** @var Action $action */
    foreach($this->actions() as $action)
    {
      $action->setTranslateDomain($translateDomain);
    }
    return $this;
  }

}