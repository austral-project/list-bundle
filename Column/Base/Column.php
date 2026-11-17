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

use Austral\Contracts\Entity\EntityInterface;
use Austral\ListBundle\Column\Interfaces\ColumnInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Austral Column.
 * @template T of object
 *
 * @implements ColumnInterface<T>
 *
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
   * @var array<string, mixed>
   */
  protected array $options;

  /**
   * @var string|null
   */
  protected ?string $class = null;

  /**
   * @var array<string, mixed>
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
   * @var \Closure(object|null): mixed|null
   */
  protected ?\Closure $getter = null;

  /**
   * @var mixed
   */
  protected mixed $value = null;

  /**
   * @var string|null
   */
  protected ?string $type = null;

  /**
   * @var string|null
   */
  protected ?string $id;

  /**
   * EntityFieldList constructor.
   *
   * @param string $fieldname
   * @param string|null $entitled
   * @param array<string, mixed> $options
   */
  public function __construct(string $fieldname, ?string $entitled = null, array $options = array())
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
  protected function configureOptions(OptionsResolver $resolver): void
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
   * @inheritDoc
   */
  public function getFieldname(): string
  {
    return $this->fieldname;
  }

  /**
   * @inheritDoc
   */
  public function setFieldname(string $fieldname): self
  {
    $this->fieldname = $fieldname;
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getSize(): ?string
  {
    return $this->size;
  }

  /**
   * @inheritDoc
   */
  public function setSize(string $size): self
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
   * @inheritDoc
   */
  public function setAlign(string $align): self
  {
    $this->align = $align;
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getEntitled(): string
  {
    return $this->entitled;
  }

  /**
   * @inheritDoc
   */
  public function setEntitled(string $entitled): ColumnInterface
  {
    $this->entitled = $entitled;
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getType(): ?string
  {
    return $this->type;
  }

  /**
   * @inheritDoc
   */
  public function getOptions(): array
  {
    return $this->options;
  }

  /**
   * @inheritDoc
   */
  public function withLanguage(): bool
  {
    return $this->withLanguage;
  }

  /**
   * @inheritDoc
   */
  public function setOptions(array $options): self
  {
    $this->options = $options;
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function class(string $class = ""): string
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
   * @inheritDoc
   */
  public function addAttr(string $key, mixed $value): self
  {
    $this->options['attr'][$key] = $value;
    return $this;
  }

  /**
   * @return string
   */
  public function attrString(): string
  {
    $return = "";
    foreach($this->attr as $key => $value)
    {
      $return .= " {$key}='{$value}'";
    }
    return $return;
  }

  /**
   * @return array<string, string>
   */
  public function attr(): array
  {
    return $this->attr;
  }

  /**
   * Get value
   * @return mixed
   */
  public function getValue(): mixed
  {
    return $this->value;
  }

  /**
   * @inheritDoc
   */
  public function setValue(mixed $value): self
  {
    $this->value = $value;
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getter(EntityInterface $object): mixed
  {
    if($this->getter instanceof \Closure)
    {
      return $this->getter->call($this, $object);
    }
    return "_function_disabled";
  }

  /**
   * @inheritDoc
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