<?php
/*
 * This file is part of the Austral List Bundle package.
 *
 * (c) Austral <support@austral.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Austral\ListBundle\Row;

use Austral\ListBundle\Column\Interfaces\ColumnActionInterface;
use Austral\ListBundle\Column\Interfaces\ColumnInterface;
use Austral\ListBundle\Column\Interfaces\ColumnWithPathInterface;
use Austral\ListBundle\Section\Section;
use Austral\ToolsBundle\AustralTools;
use Austral\EntityBundle\Entity\EntityInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Austral Row Value.
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @final
 */
class Row
{

  /**
   * @var EntityInterface
   */
  protected EntityInterface $object;

  /**
   * @var Section
   */
  protected Section $section;

  /**
   * @var PropertyAccessorInterface
   */
  protected PropertyAccessorInterface $propertyAccessor;

  /**
   * @var array
   */
  protected array $columns = array();

  /**
   * @var array
   */
  protected array $children = array();

  /**
   * @var array
   */
  protected array $columnsActions = array();

  /**
   * @var ColumnActionInterface|ColumnInterface
   */
  protected $editAction = null;

  /**
   * Row constructor.
   *
   * @param Section $section
   * @param EntityInterface $object
   */
  public function __construct(Section $section, EntityInterface $object)
  {
    $this->object = $object;
    $this->section = $section;
    $this->propertyAccessor = PropertyAccess::createPropertyAccessorBuilder()
      ->enableExceptionOnInvalidIndex()
      ->getPropertyAccessor();
  }

  /**
   * @return string
   */
  public function id(): string
  {
    return "object-{$this->object->getId()}";
  }

  /**
   * @param string|null $class
   *
   * @return string
   */
  public function class(string $class = ""): ?string
  {
    $array = array();
    if($class) $array[] = $class;
    $array[] = "row-element";
    if($this->editAction) {
      $array[] .= "has-link";
    }
    return implode(" ", $array);
  }

  /**
   * @return array
   */
  public function attr(): array
  {
    if($this->editAction) {
      $attr = $this->editAction->attr();
      $attr["data-url"] = $this->editAction->path();
      return $attr;
    }
    return array();
  }

  /**
   * @return string
   */
  public function attrString(): string
  {
    $return = "";
    foreach($this->attr() as $key => $value)
    {
      $return .= " {$key}='{$value}'";
    }
    return $return;
  }

  /**
   * @return EntityInterface
   */
  public function getObject(): EntityInterface
  {
    return $this->object;
  }

  /**
   * @return array
   */
  public function columns(): array
  {
    return $this->columns;
  }

  /**
   * @return array
   */
  public function columnsActions(): array
  {
    return $this->columnsActions;
  }

  /**
   * @return ColumnActionInterface|ColumnInterface
   */
  public function editAction(): ColumnActionInterface
  {
    return $this->editAction;
  }

  /**
   * @param ColumnInterface $column
   *
   * @return Row
   */
  public function addColumn(ColumnInterface $column): Row
  {
    $columnByObject = clone $column;
    if($column->getType() != "template")
    {
      $columnByObject->setId("{$columnByObject->getFieldname()}-{$this->object->getId()}");

      $value = $column->getter($this->object);
      if($value === "_function_disabled")
      {
        $value = $this->propertyAccessor->getValue($this->object, $column->getFieldname());
        if($column->getType() == "date" && $value)
        {
          $value = $value->format($column->getFormat());
        }
        if($column->withLanguage())
        {
          $value = $this->section->getParent()->getTranslate()->trans($value, $this->section->getParent()->getTranslateParameters(), $this->section->getTranslateDomain());
        }
      }
      $columnByObject->setValue($value);
      if($columnByObject instanceof ColumnWithPathInterface)
      {
        $this->replacePathAttributes($columnByObject);
      }
    }
    $this->columns[] = $columnByObject;
    return $this;
  }

  /**
   * @param ColumnActionInterface|ColumnWithPathInterface|ColumnInterface $columnAction
   *
   * @return Row
   */
  public function addColumnAction(ColumnActionInterface $columnAction): Row
  {
    if($columnAction->keyname() == "edit")
    {
      $this->editAction = $columnAction;
    }
    if($columnAction->keyname() == "delete")
    {
      $columnAction->addAttr("data-remove-element", "#object-{$this->object->getId()}");
    }
    $this->replacePathAttributes($columnAction);
    $this->columnsActions[] = $columnAction;
    return $this;
  }

  /**
   * @param ColumnWithPathInterface $column
   */
  protected function replacePathAttributes(ColumnWithPathInterface $column)
  {
    $pathAttributes = $column->getPathAttributes();
    $path = $column->path();
    preg_match_all("/__(\w*)__/iuU", $path, $matches, PREG_SET_ORDER);
    foreach($matches as $matche)
    {
      $getter = AustralTools::createGetterFunction($matche[1]);
      if(method_exists($this->object, $getter))
      {
        $pathAttributes[$matche[0]] = $this->object->$getter();
      }
      else
      {
        $pathAttributes[$matche[0]] = "NONE";
      }
    }
    $column->setPathAttributes($pathAttributes);
    if(!$column->translateDomain())
    {
      $column->setTranslateDomain($this->section->getTranslateDomain());
    }
  }

  /**
   * @param Row $rowChild
   *
   * @return $this
   */
  public function addChildren(Row $rowChild): Row
  {
    $this->children[$rowChild->getObject()->getId()] = $rowChild;
    return $this;
  }

  /**
   * @return array
   */
  public function children(): array
  {
    return $this->children;
  }

}