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
 * Austral Column Interface.
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
  public function setId(string $id): ColumnInterface;

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
  public function setFieldname(string $fieldname): ColumnInterface;

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
  public function setEntitled(string $entitled): ColumnInterface;

  /**
   * Get type
   * @return string
   */
  public function getType(): string;

  /**
   * Get options
   * @return array
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
   * @param array $options
   *
   * @return $this
   */
  public function setOptions(array $options): ColumnInterface;

  /**
   * Get attr
   * @return string|null
   */
  public function attrString(): ?string;

  /**
   * Get attr
   * @return array
   */
  public function attr(): array;

  /**
   * Get size
   * @return string|null
   */
  public function getSize(): ?string;

  /**
   * @param string|null $size
   *
   * @return $this
   */
  public function setSize(?string $size): ColumnInterface;

  /**
   * Get size
   * @return string|null
   */
  public function getAlign(): ?string;

  /**
   * @param string|null $align
   *
   * @return $this
   */
  public function setAlign(?string $align): ColumnInterface;

  /**
   * Get value
   * @return mixed
   */
  public function getValue();

  /**
   * @param mixed $value
   *
   * @return $this
   */
  public function setValue($value): ColumnInterface;

  /**
   * getTranslateParameters
   *
   * @return array
   */
  public function getTranslateParameters(): array;

}