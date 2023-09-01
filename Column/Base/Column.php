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

use Austral\ListBundle\Column\Interfaces\ColumnInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Austral Column.
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @abstract
 */
abstract class Column implements ColumnInterface
{
  /**
   * @var string
   */
  protected string $fieldname;

  /**
   * @var string
   */
  protected string $entitled;

  /**
   * @var array
   */
  protected array $options;

  /**
   * @var string|null
   */
  protected ?string $class = null;

  /**
   * @var array
   */
  protected array $attr = array();

  /**
   * @var bool
   */
  protected bool $withLanguage = false;

  /**
   * @var string|null
   */
  protected ?string $size = null;

  /**
   * @var string|null
   */
  protected ?string $align = null;

  /**
   * @var \Closure|null
   */
  protected ?\Closure $getter = null;

  /**
   * @var mixed
   */
  protected $value;

  /**
   * @var string
   */
  protected string $type;

  /**
   * @var string|null
   */
  protected ?string $id;

  /**
   * EntityFieldList constructor.
   *
   * @param $fieldname
   * @param string|null $entitled
   * @param array $options
   */
  public function __construct($fieldname, ?string $entitled = null, array $options = array())
  {
    $resolver = new OptionsResolver();
    $this->configureOptions($resolver);
    $this->options = $resolver->resolve($options);

    $this->fieldname = $fieldname;
    $this->entitled = $entitled ? : "fields.{$fieldname}.entitled";
    $this->withLanguage = $this->options["language"];
    $this->class = $this->options['class'];
    $this->id = $this->options['id'];
    $this->attr = $this->options['attr'];
    $this->getter = array_key_exists("getter", $this->options) ? $this->options['getter'] : null;
  }

  /**
   * @param OptionsResolver $resolver
   */
  protected function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
        "language"        =>  false,
        "class"           =>  "",
        "id"              =>  "",
        "attr"            =>  array(),
        "getter"          =>  null,
        "translateParameters" =>  array(),
        "templatePath"    => null,
      )
    );
    $resolver->setAllowedTypes('getter', array(\Closure::class, "null"))
      ->setAllowedTypes('language', array('boolean'))
      ->setAllowedTypes('class', array('string', "null"))
      ->setAllowedTypes('id', array('string', "null"))
      ->setAllowedTypes('attr', array('array'))
      ->setAllowedTypes('translateParameters', array('array'))
      ->setAllowedTypes('templatePath', array('string', "null"));
  }

  /**
   * Get fieldname
   * @return string
   */
  public function getFieldname(): string
  {
    return $this->fieldname;
  }

  /**
   * Set fieldname
   *
   * @param string $fieldname
   *
   * @return $this
   */
  public function setFieldname(string $fieldname): ColumnInterface
  {
    $this->fieldname = $fieldname;
    return $this;
  }

  /**
   * Get size
   * @return string|null
   */
  public function getSize(): ?string
  {
    return $this->size;
  }

  /**
   * @param string|null $size
   *
   * @return Column
   */
  public function setSize(?string $size): Column
  {
    $this->size = $size;
    return $this;
  }

  /**
   * Get size
   * @return string|null
   */
  public function getAlign(): ?string
  {
    return $this->align;
  }

  /**
   * @param string|null $align
   *
   * @return Column
   */
  public function setAlign(?string $align): Column
  {
    $this->align = $align;
    return $this;
  }

  /**
   * Get entitled
   * @return string
   */
  public function getEntitled(): string
  {
    return $this->entitled;
  }

  /**
   * Set entitled
   *
   * @param string $entitled
   *
   * @return $this
   */
  public function setEntitled(string $entitled): ColumnInterface
  {
    $this->entitled = $entitled;
    return $this;
  }

  /**
   * Get type
   * @return string
   */
  public function getType(): string
  {
    return $this->type;
  }

  /**
   * Get options
   * @return array
   */
  public function getOptions(): array
  {
    return $this->options;
  }

  /**
   * @return bool
   */
  public function withLanguage(): bool
  {
    return $this->withLanguage;
  }

  /**
   * Set options
   *
   * @param array $options
   *
   * @return $this
   */
  public function setOptions(array $options): ColumnInterface
  {
    $this->options = $options;
    return $this;
  }

  /**
   * @param string|null $class
   *
   * @return string
   */
  public function class(string $class = ""): ?string
  {
    return $class.($this->class ? " {$this->class}" : "");
  }

  /**
   * @return string|null
   */
  public function getId(): ?string
  {
    return $this->id;
  }

  /**
   * @param string $id
   *
   * @return $this
   */
  public function setId(string $id): Column
  {
    $this->id = $id;
    return $this;
  }

  /**
   * @param $key
   * @param $value
   *
   * @return $this
   */
  public function addAttr($key, $value): Column
  {
    $this->options['attr'][$key] = $value;
    return $this;
  }

  /**
   * @return string
   */
  public function attrString(): ?string
  {
    $return = "";
    foreach($this->attr as $key => $value)
    {
      $return .= " {$key}='{$value}'";
    }
    return $return;
  }

  /**
   * @return array
   */
  public function attr(): array
  {
    return $this->attr;
  }

  /**
   * Get value
   * @return mixed
   */
  public function getValue()
  {
    return $this->value;
  }

  /**
   * @param mixed $value
   *
   * @return $this
   */
  public function setValue($value): Column
  {
    $this->value = $value;
    return $this;
  }

  /**
   * @param $object
   *
   * @return mixed|null
   */
  public function getter($object)
  {
    if($this->getter instanceof \Closure)
    {
      return $this->getter->call($this, $object);
    }
    return "_function_disabled";
  }

  /**
   * getTranslateParameters
   *
   * @return array
   */
  public function getTranslateParameters(): array
  {
    return $this->options["translateParameters"];
  }

  /**
   * getTemplatePath
   *
   * @return string|null
   */
  public function getTemplatePath(): ?string
  {
    return $this->options["templatePath"];
  }

}