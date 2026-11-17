<?php
/*
 * This file is part of the Austral List Bundle package.
 *
 * (c) Austral <support@austral.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Austral\ListBundle\Section;

use Austral\Contracts\Entity\EntityInterface;
use Austral\ListBundle\Column\Interfaces\ColumnActionInterface;
use Austral\ListBundle\Column\Interfaces\ColumnInterface;
use Austral\ListBundle\Mapper\Base\MapperElement;
use Austral\ListBundle\Mapper\ListMapper;
use Austral\ListBundle\Row\Row;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Symfony\Component\String\u;

/**
 * Austral Section Value.
 * @template T of object
 *
 * @extends MapperElement<T>
 *
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @final
 */
class Section extends MapperElement
{

  /**
   * @var ListMapper<T>
   */
  protected ListMapper $parent;

  /**
   * @var string
   */
  protected string $keyname;

  /**
   * @var array<string, Row<T>>
   */
  protected array $rows = array();

  /**
   * @var array<string, Row<T>>
   */
  protected array $allRows = array();

  /**
   * @var bool
   */
  protected bool $objectInitialise = false;

  /**
   * @var array<int, T>
   */
  protected array $objects = array();

  /**
   * @var bool
   */
  protected bool $columnActionsDefined = false;

  /**
   * @var array<int, ColumnInterface<T>>
   */
  protected array $headerColumns = array();

  /**
   * @var int
   */
  protected int $countAll = 0;

  /**
   * @var int
   */
  protected int $countPaginator = 0;

  /**
   * @var string
   */
  protected string $mapperType = "table";

  /**
   * @var \Closure(object|null):mixed|null
   */
  protected ?\Closure $childrenRowMethod = null;

  /**
   * @var array<string, mixed>
   */
  protected array $sortableOptions = array();

  /**
   * Section constructor
   *
   * @param ListMapper<T> $parent
   * @param string $keyname
   */
  public function __construct(ListMapper $parent, string $keyname)
  {
    $this->parent = $parent;
    $this->keyname = u($keyname)->snake()->toString();
    $this->setSortableOptions(array());
  }

  /**
   * @return array<string, mixed>
   */
  public function getSortableOptions(): array
  {
    return $this->sortableOptions;
  }

  /**
   * @param array<string, mixed> $sortableOptions
   *
   * @return $this
   */
  public function setSortableOptions(array $sortableOptions): self
  {
    $resolver = new OptionsResolver();
    $resolver->setDefaults(array(
        "group"               =>  null,
        "parent"              =>  null
      )
    );
    $this->sortableOptions = $resolver->resolve($sortableOptions);
    return $this;
  }

  /**
   * @return ListMapper<T>
   */
  public function end(): ListMapper
  {
    return $this->parent;
  }

  /**
   * @param string $title
   *
   * @return $this
   */
  public function setTitle(string $title): self
  {
    $this->title = $title;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getTitle(): ?string
  {
    return $this->title ?? $this->parent->getTitle();
  }

  /**
   * @param string|null $subTitle
   *
   * @return $this
   */
  public function setSubTitle(?string $subTitle): self
  {
    $this->subTitle = $subTitle;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getSubTitle(): ?string
  {
    return $this->subTitle ?? $this->keyname == "default" ? $this->parent->getSubTitle() : null;
  }

  /**
   * @return string
   */
  public function getKeyname(): string
  {
    return $this->keyname;
  }

  /**
   * @param string $keyname
   *
   * @return $this
   */
  public function setKeyname(string $keyname): self
  {
    $this->keyname = $keyname;
    return $this;
  }

  /**
   * @return bool
   */
  public function columnActionsDefined(): bool
  {
    return $this->columnActionsDefined;
  }

  /**
   * getObjectInitialise
   *
   * @return bool
   */
  public function getObjectInitialise(): bool
  {
    return $this->objectInitialise;
  }

  /**
   * @param bool $objectInitialise
   * @return $this
   */
  public function setObjectInitialise(bool $objectInitialise): self
  {
    $this->objectInitialise = $objectInitialise;
    return $this;
  }

  /**
   * @return array<int, T>
   */
  public function getObjects(): array
  {
    return $this->objects;
  }

  /**
   * @param array<T> $objects
   *
   * @return $this
   */
  public function setObjects(array $objects): self
  {
    $this->setObjectInitialise(true);
    $this->objects = $objects;
    return $this;
  }

  /**
   * @param int $countAll
   *
   * @return $this
   */
  public function setCountAll(int $countAll = 0): self
  {
    $this->countAll = $countAll;
    return $this;
  }

  /**
   * @return int
   */
  public function countAll(): int
  {
    return $this->countAll;
  }

  /**
   * @return int
   */
  public function getCountPaginator(): int
  {
    return $this->countPaginator;
  }

  /**
   * @param int $countPaginator
   *
   * @return $this
   */
  public function setCountPaginator(int $countPaginator): self
  {
    $this->countPaginator = $countPaginator;
    return $this;
  }

  /**
   * @param \Closure(object|null):mixed $method
   *
   * @return $this
   */
  public function childrenRow(\Closure $method): self
  {
    $this->childrenRowMethod = $method;
    return $this;
  }

  /**
   * @return $this
   */
  public function generateRows(): self
  {
    /** @var EntityInterface<T> $object */
    foreach($this->getObjects() as $object)
    {
      $row = new Row($this, $object);
      foreach($this->headerColumns as $column)
      {
        $row->addColumn($column);
      }
      if($this->childrenRowMethod)
      {
        $this->addChildrenRow($row);
      }
      if(!array_key_exists($object->getId(), $this->allRows))
      {
        $this->allRows[$object->getId()] = $row;
        $this->rows[$object->getId()] = $row;
      }
    }
    return $this;
  }

  /**
   * @param Row<T> $rowParent
   */
  protected function addChildrenRow(Row $rowParent): void
  {

    if (\is_callable($this->childrenRowMethod))
    {
      $children = call_user_func_array($this->childrenRowMethod, array(
        $rowParent->getObject()
      ));
    }
    else
    {
      $children = $rowParent->getObject()->{$this->childrenRowMethod}();
    }
    if($children)
    {
      /** @var EntityInterface<T> $object */
      foreach($children as $object)
      {
        $rowChild = new Row($this, $object);
        foreach($this->headerColumns as $column)
        {
          $rowChild->addColumn($column);
        }
        $this->addChildrenRow($rowChild);
        $rowParent->addChildren($rowChild);
        $this->allRows[$object->getId()] = $rowChild;
      }
    }
  }


  /**
   * @return array<int, ColumnInterface<T>>
   */
  public function headerColumns(): array
  {
    return $this->headerColumns;
  }

  /**
   * @return array<string, Row<T>>
   */
  public function rows(): array
  {
    return $this->rows;
  }

  /**
   * @param array<ColumnInterface<T>> $columns
   *
   * @return $this
   */
  public function addColumns(array $columns): self
  {
    $this->headerColumns = array_merge($columns, $this->headerColumns);
    return $this;
  }

  /**
   * @param ColumnActionInterface<T> $action
   *
   * @return $this
   */
  public function addColumnAction(ColumnActionInterface $action): self
  {
    $this->columnActionsDefined = true;
    /** @var Row<T> $row */
    foreach ($this->allRows as $row)
    {
      $actionByRow = clone $action;
      $row->addColumnAction($actionByRow);
    }
    return $this;
  }

  /**
   * @return ListMapper<T>
   */
  public function getParent(): ListMapper
  {
    return $this->parent;
  }

  /**
   * @param ListMapper<T> $parent
   *
   * @return $this
   */
  public function setParent(ListMapper $parent): self
  {
    $this->parent = $parent;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getFilterKeyname(): ?string
  {
    return $this->filterKeyname ? : $this->parent->getFilterKeyname();
  }

  /**
   * @return float|int
   */
  public function getNbPages(): float|int
  {
    return $this->countPaginator > 0 ? ceil($this->countPaginator / $this->getMaxResult()) : 0;
  }

}