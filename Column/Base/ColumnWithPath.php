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
   * @var array
   */
  protected array $pathAttributes = array();

  /**
   * EntityFieldList constructor.
   *
   * @param $fieldname
   * @param string|null $entitled
   * @param string|null $path
   * @param array $options
   */
  public function __construct($fieldname, string $entitled = null, string $path = null, array $options = array())
  {
    parent::__construct($fieldname, $entitled, $options);
    $this->path = $path;
  }

  /**
   * @param OptionsResolver $resolver
   */
  protected function configureOptions(OptionsResolver $resolver)
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
   * @param array $pathAttributes
   *
   * @return string|null
   */
  public function path(array $pathAttributes = array()): ?string
  {
    $pathAttributes = array_merge($this->pathAttributes, $pathAttributes);
    return str_replace(array_keys($pathAttributes), array_values($pathAttributes), $this->path);
  }

  /**
   * @param string|null $path
   *
   * @return $this
   */
  public function setPath(?string $path): ColumnWithPath
  {
    $this->path = $path;
    return $this;
  }

  /**
   * @param array $pathAttributes
   *
   * @return $this
   */
  public function setPathAttributes(array $pathAttributes): ColumnWithPath
  {
    $this->pathAttributes = $pathAttributes;
    return $this;
  }

  /**
   * @return array
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
  public function setTranslateDomain(string $translateDomain): ColumnWithPath
  {
    $this->options['translateDomain'] = $translateDomain;
    return $this;
  }

  /**
   * @return array
   */
  public function translateParameters(): array
  {
    return $this->options['translateParameters'];
  }

  /**
   * @param array $translateParameters
   *
   * @return $this
   */
  public function setTranslateParameters(array $translateParameters): ColumnWithPath
  {
    $this->options['translateParameters'] = $translateParameters;
    return $this;
  }


}