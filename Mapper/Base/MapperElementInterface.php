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
 * Austral Mapper Element Interface.
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @implements
 */
interface MapperElementInterface
{

  /**
   * @return string
   */
  public function getTitle(): string;

  /**
   * @param string $title
   *
   * @return MapperElementInterface
   */
  public function setTitle(string $title): MapperElementInterface;

  /**
   * @return string
   */
  public function getSubTitle(): ?string;

  /**
   * @param string $subTitle
   *
   * @return MapperElementInterface
   */
  public function setSubTitle(string $subTitle): MapperElementInterface;

  /**
   * @param ColumnInterface $column
   * @param string|null $size
   * @param string|null $align
   *
   * @return MapperElementInterface
   */
  public function addColumn(ColumnInterface $column, ?string $size = ListMapper::SIZE_AUTO, ?string $align = ListMapper::COL_ALIGN_LEFT): MapperElementInterface;

  /**
   * @return string
   */
  public function getTranslateDomain(): string;

  /**
   * @param string $translateDomain
   *
   * @return MapperElementInterface
   */
  public function setTranslateDomain(string $translateDomain): MapperElementInterface;

  /**
   * @return FilterMapperInterface
   */
  public function getFilter(): FilterMapperInterface;
  /**
   * @param FilterMapperInterface $filter
   *
   * @return MapperElementInterface
   */
  public function setFilter(FilterMapperInterface $filter): MapperElementInterface;

  /**
   * @return string
   */
  public function getMapperType(): string;

  /**
   * @param DataHydrateInterface $dataHydrate
   *
   * @return $this
   */
  public function setDataHydrate(DataHydrateInterface $dataHydrate): MapperElementInterface;

  /**
   * @param \Closure|null $closure
   *
   * @return $this
   */
  public function buildDataHydrate(\Closure $closure = null): MapperElementInterface;

  /**
   * @param string $mapperType
   *
   * @return MapperElementInterface
   */
  public function setMapperType(string $mapperType): MapperElementInterface;

  /**
   * @return int|null
   */
  public function getMaxResult(): ?int;

  /**
   * @param int|null $maxResult
   *
   * @return MapperElementInterface
   */
  public function setMaxResult(?int $maxResult): MapperElementInterface;

  /**
   * @return int
   */
  public function getOffset(): int;

  /**
   * @param int $offset
   *
   * @return MapperElementInterface
   */
  public function setOffset(int $offset): MapperElementInterface;

  /**
   * @return int
   */
  public function getNumPage(): int;

  /**
   * @param int $numPage
   *
   * @return MapperElementInterface
   */
  public function setNumPage(int $numPage): MapperElementInterface;

  /**
   * @return string
   */
  public function getTypePagination(): string;

  /**
   * @param string $typePagination
   *
   * @return MapperElementInterface
   */
  public function setTypePagination(string $typePagination): MapperElementInterface;

}