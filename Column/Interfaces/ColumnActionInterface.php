<?php
/*
 * This file is part of the Austral List Bundle package.
 *
 * (c) Austral <support@austral.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Austral\ListBundle\Column\Interfaces;

/**
 * Austral Action Column Interface.
 * @template T of object
 *
 * @extends ColumnInterface<T>
 * @extends ColumnWithPathInterface<T>
 *
 * @author Matthieu Beurel <matthieu@austral.dev>
 */
interface ColumnActionInterface extends ColumnInterface, ColumnWithPathInterface
{

  /**
   * Get keyname
   * @return string
   */
  public function keyname(): string;

  /**
   * @param string $keyname
   *
   * @return $this
   */
  public function setKeyname(string $keyname): self;

  /**
   * Get picto
   * @return string|null
   */
  public function picto(): ?string;

  /**
   * @param string|null $picto
   *
   * @return $this
   */
  public function setPicto(?string $picto): self;
}