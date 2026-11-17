<?php
/*
 * This file is part of the Austral List Bundle package.
 *
 * (c) Austral <support@austral.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Austral\ListBundle\Filter;

use Doctrine\ORM\QueryBuilder;

/**
 * Austral Filter Interface.
 * @template T of object
 *
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @interface
 */
interface FilterInterface
{

  /**
   * @param QueryBuilder $queryBuilder
   * @param bool $addJoinSelect
   *
   * @return QueryBuilder
   */
  public function generateQueryBuilder(QueryBuilder $queryBuilder, bool $addJoinSelect = true): QueryBuilder;

}