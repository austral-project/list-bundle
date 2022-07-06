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

use Austral\EntityBundle\Entity\EntityInterface;
use Austral\ListBundle\Column\Interfaces\ColumnActionInterface;
use Austral\ListBundle\Column\Interfaces\ColumnInterface;
use Austral\ListBundle\Mapper\Base\MapperElement;
use Austral\ListBundle\Mapper\ListMapper;
use Austral\ListBundle\Row\Row;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Symfony\Component\String\u;

/**
 * Austral Section Value.
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @final
 */
class Section extends MapperElement
{

  /**
   * @var ListMapper
   */
  protected ListMapper $parent;

  /**
   * @var string
   */
  protected string $keyname;

  /**
   * @var array
   */
  protected array $rows = array();

  /**
   * @var array
   */
  protected array $allRows = array();

  /**
   * @var array
   */
  protected array $objects = array();

  /**
   * @var bool
   */
  protected bool $columnActionsDefined = false;

  /**
   * @var array
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
   * @var mixed
   */
  protected $childrenRowMethod = null;

  /**
   * @var array
   */
  protected array $sortableOptions = array();

  public function __construct(ListMapper $parent, string $keyname)
  {
    $this->parent = $parent;
    $this->keyname = u($keyname)->snake()->toString();
    $this->setSortableOptions(array());
  }

  /**
   * @return array
   */
  public function getSortableOptions(): array
  {
    return $this->sortableOptions;
  }

  /**
   * @param array $sortableOptions
   *
   * @return Section
   */
  public function setSortableOptions(array $sortableOptions): Section
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
   * @return ListMapper
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
  public function setTitle(string $title): Section
  {
    $this->title = $title;
    return $this;
  }

  /**
   * @return string
   */
  public function getTitle(): string
  {
    return $this->title ?? $this->parent->getTitle();
  }

  /**
   * @param string|null $subTitle
   *
   * @return $this
   */
  public function setSubTitle(?string $subTitle): Section
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
  public function setKeyname(string $keyname): Section
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
   * @return array
   */
  public function getObjects(): array
  {
    return $this->objects;
  }

  /**
   * @param array $objects
   *
   * @return $this
   */
  public function setObjects(array $objects): Section
  {
    $this->objects = $objects;
    return $this;
  }

  /**
   * @param int $countAll
   *
   * @return $this
   */
  public function setCountAll(int $countAll = 0): Section
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
  public function setCountPaginator(int $countPaginator): Section
  {
    $this->countPaginator = $countPaginator;
    return $this;
  }

  /**
   * @param $method
   *
   * @return $this
   */
  public function childrenRow($method): Section
  {
    $this->childrenRowMethod = $method;
    return $this;
  }

  /**
   * @return $this
   */
  public function generateRows(): Section
  {
    /** @var EntityInterface $object */
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
   * @param Row $rowParent
   */
  protected function addChildrenRow(Row $rowParent)
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
      /** @var EntityInterface $object */
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
   * @return array
   */
  public function headerColumns(): array
  {
    return $this->headerColumns;
  }

  /**
   * @return array
   */
  public function rows(): array
  {
    return $this->rows;
  }

  /**
   * @param array $columns
   *
   * @return Section
   */
  public function addColumns(array $columns): Section
  {
    $this->headerColumns = array_merge($columns, $this->headerColumns);
    return $this;
  }

  /**
   * @param ColumnActionInterface|ColumnInterface $action
   *
   * @return $this
   */
  public function addColumnAction(ColumnActionInterface $action): Section
  {
    $this->columnActionsDefined = true;
    /** @var Row $row */
    foreach ($this->allRows as $row)
    {
      $actionByRow = clone $action;
      $row->addColumnAction($actionByRow);
    }
    return $this;
  }

  /**
   * @return ListMapper
   */
  public function getParent(): ListMapper
  {
    return $this->parent;
  }

  /**
   * @param ListMapper $parent
   *
   * @return $this
   */
  public function setParent(ListMapper $parent): Section
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
   * @return false|float
   */
  public function getNbPages()
  {
    return $this->countPaginator > 0 ? ceil($this->countPaginator / $this->getMaxResult()) : 0;
  }

}