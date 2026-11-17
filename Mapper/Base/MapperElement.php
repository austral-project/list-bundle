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
 * @template T of object
 *
 * @implements MapperElementInterface<T>
 *
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
   * @var array<int, ColumnInterface<T>>
   */
  protected array $headerColumns = array();

  /**
   * @var string|null
   */
  protected ?string $filterKeyname = null;

  /**
   * @var DataHydrateInterface<T>|null
   */
  protected ?DataHydrateInterface $dataHydrate = null;

  /**
   * @var FilterMapperInterface<T>|null
   */
  protected ?FilterMapperInterface $filter = null;

  /**
   * @var string
   */
  protected string $typePagination = ListMapper::PAGINATION_DEFAULT;

  /**
   * @inheritDoc
   */
  public function getDataHydrate(): ?DataHydrateInterface
  {
    return $this->dataHydrate;
  }

  /**
   * @inheritDoc
   */
  public function setDataHydrate(DataHydrateInterface $dataHydrate): self
  {
    $this->dataHydrate = $dataHydrate;
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function buildDataHydrate(?\Closure $closure = null): self
  {
    if($closure instanceof \Closure)
    {
      $closure->call($this, $this->dataHydrate);
    }
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getTitle(): ?string
  {
    return $this->title;
  }

  /**
   * @inheritDoc
   */
  public function setTitle(string $title): self
  {
    $this->title = $title;
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getSubTitle(): ?string
  {
    return $this->subTitle;
  }

  /**
   * @inheritDoc
   */
  public function setSubTitle(string $subTitle): self
  {
    $this->subTitle = $subTitle;
    return $this;
  }

  /**
   * @param ColumnInterface<T> $column
   * @param string|null $size
   * @param string|null $align
   *
   * @return $this
   */
  public function addColumn(ColumnInterface $column, ?string $size = ListMapper::SIZE_AUTO, ?string $align = ListMapper::COL_ALIGN_LEFT): self
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
   * @return $this
   */
  public function setTranslateDomain(string $translateDomain): self
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
   * @return $this
   */
  public function setFilterKeyname(?string $filterKeyname): self
  {
    $this->filterKeyname = $filterKeyname;
    return $this;
  }

  /**
   * @return FilterMapperInterface<T>|null
   */
  public function getFilter(): ?FilterMapperInterface
  {
    return $this->filter;
  }

  /**
   * @param FilterMapperInterface<T> $filter
   *
   * @return $this
   */
  public function setFilter(FilterMapperInterface $filter): self
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
   * @return $this
   */
  public function setMapperType(string $mapperType): self
  {
    $this->mapperType = $mapperType;
    return $this;
  }

  /**
   * @return int|null
   */
  public function getMaxResult(): ?int
  {
    return $this->dataHydrate?->getMaxResult();
  }

  /**
   * @param int|null $maxResult
   *
   * @return $this
   */
  public function setMaxResult(?int $maxResult): self
  {
    $this->dataHydrate?->setMaxResult($maxResult);
    return $this;
  }

  /**
   * @return int
   */
  public function getOffset(): int
  {
    return $this->dataHydrate?->getOffset() ?? 0;
  }

  /**
   * @param int $offset
   *
   * @return $this
   */
  public function setOffset(int $offset): self
  {
    $this->dataHydrate?->setOffset($offset);
    return $this;
  }

  /**
   * @return int
   */
  public function getNumPage(): int
  {
    return $this->dataHydrate?->getNumPage() ?? 1;
  }

  /**
   * @param int $numPage
   *
   * @return $this
   */
  public function setNumPage(int $numPage): self
  {
    $this->dataHydrate?->setNumPage($numPage);
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
   * @return $this
   */
  public function setTypePagination(string $typePagination): self
  {
    $this->typePagination = $typePagination;
    return $this;
  }

}