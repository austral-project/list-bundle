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
 * @author Matthieu Beurel <matthieu@austral.dev>
 */
interface ColumnActionInterface
{

  /**
   * Get keyname
   * @return string
   */
  public function keyname(): string;

  /**
   * @param string $keyname
   *
   * @return ColumnActionInterface
   */
  public function setKeyname(string $keyname): ColumnActionInterface;

  /**
   * Get picto
   * @return string|null
   */
  public function picto(): ?string;

  /**
   * @param string|null $picto
   *
   * @return ColumnActionInterface
   */
  public function setPicto(?string $picto): ColumnActionInterface;
}