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
 * @template T of object
 *
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @interface
 */
interface MapperElementInterface
{

  /**
   * @return string|null
   */
  public function getTitle(): ?string;

  /**
   * @param string $title
   *
   * @return $this
   */
  public function setTitle(string $title): self;

  /**
   * @return string|null
   */
  public function getSubTitle(): ?string;

  /**
   * @param string $subTitle
   *
   * @return $this
   */
  public function setSubTitle(string $subTitle): self;

  /**
   * @param ColumnInterface<T> $column
   * @param string|null $size
   * @param string|null $align
   *
   * @return $this
   */
  public function addColumn(ColumnInterface $column, ?string $size = ListMapper::SIZE_AUTO, ?string $align = ListMapper::COL_ALIGN_LEFT): self;

  /**
   * @return string
   */
  public function getTranslateDomain(): string;

  /**
   * @param string $translateDomain
   *
   * @return $this
   */
  public function setTranslateDomain(string $translateDomain): self;

  /**
   * @return FilterMapperInterface<T>|null
   */
  public function getFilter(): ?FilterMapperInterface;

  /**
   * @param FilterMapperInterface<T> $filter
   *
   * @return $this
   */
  public function setFilter(FilterMapperInterface $filter): self;

  /**
   * @return string
   */
  public function getMapperType(): string;

  /**
   * @return DataHydrateInterface<T>|null
   */
  public function getDataHydrate(): ?DataHydrateInterface;

  /**
   * @param DataHydrateInterface<T> $dataHydrate
   *
   * @return $this
   */
  public function setDataHydrate(DataHydrateInterface $dataHydrate): self;

  /**
   * @param \Closure(object|null): mixed|null $closure
   *
   * @return $this
   */
  public function buildDataHydrate(?\Closure $closure = null): self;

  /**
   * @param string $mapperType
   *
   * @return $this
   */
  public function setMapperType(string $mapperType): self;

  /**
   * @return int|null
   */
  public function getMaxResult(): ?int;

  /**
   * @param int|null $maxResult
   *
   * @return $this
   */
  public function setMaxResult(?int $maxResult): self;

  /**
   * @return int
   */
  public function getOffset(): int;

  /**
   * @param int $offset
   *
   * @return $this
   */
  public function setOffset(int $offset): self;

  /**
   * @return int
   */
  public function getNumPage(): int;

  /**
   * @param int $numPage
   *
   * @return $this
   */
  public function setNumPage(int $numPage): self;

  /**
   * @return string
   */
  public function getTypePagination(): string;

  /**
   * @param string $typePagination
   *
   * @return $this
   */
  public function setTypePagination(string $typePagination): self;

}