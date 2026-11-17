<?php
/*
 * This file is part of the Austral List Bundle package.
 *
 * (c) Austral <support@austral.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Austral\ListBundle\Column\Base;

use Austral\ListBundle\Column\Interfaces\ColumnWithPathInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Austral Column With Path.
 * @template T of object
 *
 * @extends  Column<T>
 * @implements ColumnWithPathInterface<T>
 *
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @abstract
 */
abstract class ColumnWithPath extends Column implements ColumnWithPathInterface
{

  /**
   * @var string|null
   */
  protected ?string $path;

  /**
   * @var array<string, mixed>
   */
  protected array $pathAttributes = array();

  /**
   * EntityFieldList constructor.
   *
   * @param string $fieldname
   * @param string|null $entitled
   * @param string|null $path
   * @param array<string, mixed> $options
   */
  public function __construct(string $fieldname, ?string $entitled = null, ?string $path = null, array $options = array())
  {
    parent::__construct($fieldname, $entitled, $options);
    $this->path = $path;
  }

  /**
   * @param OptionsResolver $resolver
   */
  protected function configureOptions(OptionsResolver $resolver): void
  {
    parent::configureOptions($resolver);
    $resolver->setDefaults(array(
        "data-url"            =>  false,
        "language"            =>  false,
        "id"                  =>  "",
        "class"               =>  "",
        "attr"                =>  array(),
        "choices"             =>  array(),
        "translateDomain"     =>  null,
        "translateParameters" =>  array(),
      )
    );
  }

  /**
   * @param array<string, mixed> $pathAttributes
   *
   * @return string|null
   */
  public function path(array $pathAttributes = array()): ?string
  {
    $pathAttributes = array_merge($this->pathAttributes, $pathAttributes);
    return $this->path ? str_replace(array_keys($pathAttributes), array_values($pathAttributes), $this->path) : null;
  }

  /**
   * @param string|null $path
   *
   * @return $this
   */
  public function setPath(?string $path): self
  {
    $this->path = $path;
    return $this;
  }

  /**
   * @param array<string, mixed> $pathAttributes
   *
   * @return $this
   */
  public function setPathAttributes(array $pathAttributes): self
  {
    $this->pathAttributes = $pathAttributes;
    return $this;
  }

  /**
   * @return array<string, mixed>
   */
  public function getPathAttributes(): array
  {
    return $this->pathAttributes;
  }

  /**
   * @return string|null
   */
  public function translateDomain(): ?string
  {
    return $this->options['translateDomain'];
  }

  /**
   * @param string $translateDomain
   *
   * @return $this
   */
  public function setTranslateDomain(string $translateDomain): self
  {
    $this->options['translateDomain'] = $translateDomain;
    return $this;
  }

  /**
   * @return array<string, mixed>
   */
  public function translateParameters(): array
  {
    return $this->options['translateParameters'];
  }

  /**
   * @param array<string, mixed> $translateParameters
   *
   * @return $this
   */
  public function setTranslateParameters(array $translateParameters): self
  {
    $this->options['translateParameters'] = $translateParameters;
    return $this;
  }


}