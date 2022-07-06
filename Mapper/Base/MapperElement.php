<?php
/*
 * This file is part of the Austral List Bundle package.
 *
 * (c) Austral <support@austral.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Austral\ListBundle\Mapper\Base;

use Austral\ListBundle\Column\Interfaces\ColumnInterface;
use Austral\ListBundle\DataHydrate\DataHydrateInterface;
use Austral\ListBundle\Filter\FilterMapperInterface;
use Austral\ListBundle\Mapper\ListMapper;

/**
 * Austral Mapper Element.
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @abstract
 */
abstract class MapperElement implements MapperElementInterface
{

  /**
   * @var string|null
   */
  protected ?string $title = null;

  /**
   * @var string|null
   */
  protected ?string $subTitle = null;

  /**
   * @var string
   */
  protected string $translateDomain = "";

  /**
   * @var string
   */
  protected string $mapperType = "table";

  /**
   * @var array
   */
  protected array $headerColumns = array();

  /**
   * @var string|null
   */
  protected ?string $filterKeyname = null;

  /**
   * @var DataHydrateInterface
   */
  protected DataHydrateInterface $dataHydrate;

  /**
   * @var FilterMapperInterface|null
   */
  protected ?FilterMapperInterface $filter = null;

  /**
   * @var string
   */
  protected string $typePagination = ListMapper::PAGINATION_DEFAULT;

  /**
   * @return DataHydrateInterface
   */
  public function getDataHydrate(): DataHydrateInterface
  {
    return $this->dataHydrate;
  }

  /**
   * @param DataHydrateInterface $dataHydrate
   *
   * @return $this
   */
  public function setDataHydrate(DataHydrateInterface $dataHydrate): MapperElementInterface
  {
    $this->dataHydrate = $dataHydrate;
    return $this;
  }

  /**
   * @param \Closure|null $closure
   *
   * @return $this
   */
  public function buildDataHydrate(\Closure $closure = null): MapperElementInterface
  {
    if($closure instanceof \Closure)
    {
      $closure->call($this, $this->dataHydrate);
    }
    return $this;
  }

  /**
   * @return string
   */
  public function getTitle(): string
  {
    return $this->title;
  }

  /**
   * @param string $title
   *
   * @return MapperElementInterface
   */
  public function setTitle(string $title): MapperElementInterface
  {
    $this->title = $title;
    return $this;
  }

  /**
   * @return string
   */
  public function getSubTitle(): ?string
  {
    return $this->subTitle;
  }

  /**
   * @param string $subTitle
   *
   * @return MapperElementInterface
   */
  public function setSubTitle(string $subTitle): MapperElementInterface
  {
    $this->subTitle = $subTitle;
    return $this;
  }

  /**
   * @param ColumnInterface $column
   * @param string|null $size
   * @param string|null $align
   *
   * @return MapperElementInterface
   */
  public function addColumn(ColumnInterface $column, ?string $size = ListMapper::SIZE_AUTO, ?string $align = ListMapper::COL_ALIGN_LEFT): MapperElementInterface
  {
    $this->headerColumns[] = $column->setSize($size ?? ListMapper::SIZE_AUTO)->setAlign($align ?? ListMapper::COL_ALIGN_LEFT);
    return $this;
  }

  /**
   * @return string
   */
  public function getTranslateDomain(): string
  {
    return $this->translateDomain;
  }

  /**
   * @param string $translateDomain
   *
   * @return MapperElementInterface
   */
  public function setTranslateDomain(string $translateDomain): MapperElementInterface
  {
    $this->translateDomain = $translateDomain;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getFilterKeyname(): ?string
  {
    return $this->filterKeyname;
  }

  /**
   * @param string|null $filterKeyname
   *
   * @return MapperElementInterface
   */
  public function setFilterKeyname(?string $filterKeyname): MapperElementInterface
  {
    $this->filterKeyname = $filterKeyname;
    return $this;
  }

  /**
   * @return FilterMapperInterface
   */
  public function getFilter(): FilterMapperInterface
  {
    return $this->filter;
  }

  /**
   * @param FilterMapperInterface $filter
   *
   * @return MapperElementInterface
   */
  public function setFilter(FilterMapperInterface $filter): MapperElementInterface
  {
    $this->filter = $filter;
    return $this;
  }

  /**
   * @return string
   */
  public function getMapperType(): string
  {
    return $this->mapperType;
  }

  /**
   * @param string $mapperType
   *
   * @return MapperElementInterface
   */
  public function setMapperType(string $mapperType): MapperElementInterface
  {
    $this->mapperType = $mapperType;
    return $this;
  }

  /**
   * @return int|null
   */
  public function getMaxResult(): ?int
  {
    return $this->dataHydrate->getMaxResult();
  }

  /**
   * @param int|null $maxResult
   *
   * @return MapperElementInterface
   */
  public function setMaxResult(?int $maxResult): MapperElementInterface
  {
    $this->dataHydrate->setMaxResult($maxResult);
    return $this;
  }

  /**
   * @return int
   */
  public function getOffset(): int
  {
    return $this->dataHydrate->getOffset();
  }

  /**
   * @param int $offset
   *
   * @return MapperElementInterface
   */
  public function setOffset(int $offset): MapperElementInterface
  {
    $this->dataHydrate->setOffset($offset);
    return $this;
  }

  /**
   * @return int
   */
  public function getNumPage(): int
  {
    return $this->dataHydrate->getNumPage();
  }

  /**
   * @param int $numPage
   *
   * @return MapperElementInterface
   */
  public function setNumPage(int $numPage): MapperElementInterface
  {
    $this->dataHydrate->setNumPage($numPage);
    return $this;
  }

  /**
   * @return string
   */
  public function getTypePagination(): string
  {
    return $this->typePagination;
  }

  /**
   * @param string $typePagination
   *
   * @return MapperElementInterface
   */
  public function setTypePagination(string $typePagination): MapperElementInterface
  {
    $this->typePagination = $typePagination;
    return $this;
  }

}