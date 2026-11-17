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
 * Austral Column With Path Interface.
 * @template T of object
 *
 * @author Matthieu Beurel <matthieu@austral.dev>
 */
interface ColumnWithPathInterface
{

  /**
   * @param string|null $path
   *
   * @return $this
   */
  public function setPath(?string $path): self;

  /**
   * @return string|null
   */
  public function path(): ?string;

  /**
   * @param array<string, mixed> $pathAttributes
   *
   * @return $this
   */
  public function setPathAttributes(array $pathAttributes): self;

  /**
   * @return array<string, mixed>
   */
  public function getPathAttributes(): array;

  /**
   * @return string|null
   */
  public function translateDomain(): ?string;

  /**
   * @param string $translateDomain
   *
   * @return $this
   */
  public function setTranslateDomain(string $translateDomain): self;

}