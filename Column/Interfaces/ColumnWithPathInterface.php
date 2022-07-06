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

use Austral\ListBundle\Column\Base\ColumnWithPath;

/**
 * Austral Column With Path Interface.
 * @author Matthieu Beurel <matthieu@austral.dev>
 */
interface ColumnWithPathInterface
{

  /**
   * @param string|null $path
   *
   * @return $this
   */
  public function setPath(?string $path): ColumnWithPathInterface;

  /**
   * @return string|null
   */
  public function path(): ?string;

  /**
   * @param array $pathAttributes
   *
   * @return ColumnWithPathInterface
   */
  public function setPathAttributes(array $pathAttributes): ColumnWithPathInterface;

  /**
   * @return array
   */
  public function getPathAttributes(): array;

  /**
   * @return string|null
   */
  public function translateDomain(): ?string;

  /**
   * @param string $translateDomain
   *
   * @return ColumnWithPath
   */
  public function setTranslateDomain(string $translateDomain): ColumnWithPath;

}