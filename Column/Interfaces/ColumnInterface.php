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

use Austral\Contracts\Entity\EntityInterface;

/**
 * Austral Column Interface.
 * @template T of object
 *
 * @author Matthieu Beurel <matthieu@austral.dev>
 */
interface ColumnInterface
{

  /**
   * @return string|null
   */
  public function getId(): ?string;

  /**
   * @param string $id
   *
   * @return $this
   */
  public function setId(string $id): self;

  /**
   * Get fieldname
   * @return string
   */
  public function getFieldname(): string;

  /**
   * Set fieldname
   *
   * @param string $fieldname
   *
   * @return $this
   */
  public function setFieldname(string $fieldname): self;

  /**
   * Get entitled
   * @return string
   */
  public function getEntitled(): string;

  /**
   * Set entitled
   *
   * @param string $entitled
   *
   * @return $this
   */
  public function setEntitled(string $entitled): self;

  /**
   * Get type
   * @return string|null
   */
  public function getType(): ?string;

  /**
   * Get options
   * @return array<string, mixed>
   */
  public function getOptions(): array;

  /**
   * Get withLanguage
   * @return boolean
   */
  public function withLanguage(): bool;

  /**
   * Set options
   *
   * @param array<string, mixed> $options
   *
   * @return $this
   */
  public function setOptions(array $options): self;

  /**
   * Get attr
   * @return string|null
   */
  public function attrString(): ?string;

  /**
   * Get attr
   * @return array<string, mixed>
   */
  public function attr(): array;

  /**
   * Get size
   * @return string|null
   */
  public function getSize(): ?string;

  /**
   * @param string $size
   *
   * @return $this
   */
  public function setSize(string $size): self;

  /**
   * Get size
   * @return string|null
   */
  public function getAlign(): ?string;

  /**
   * @param string $align
   *
   * @return $this
   */
  public function setAlign(string $align): self;

  /**
   * Get value
   * @return mixed
   */
  public function getValue(): mixed;

  /**
   * @param mixed $value
   *
   * @return $this
   */
  public function setValue(mixed $value): self;

  /**
   * getTranslateParameters
   *
   * @return array<string, mixed>
   */
  public function getTranslateParameters(): array;

  /**
   * class
   *
   * @param string $class
   * @return string
   */
  public function class(string $class = ""): string;

  /**
   * @param EntityInterface<T> $object
   *
   * @return mixed
   */
  public function getter(EntityInterface $object): mixed;

  /**
   * @param string $key
   * @param mixed $value
   *
   * @return $this
   */
  public function addAttr(string $key, mixed $value): self;

}