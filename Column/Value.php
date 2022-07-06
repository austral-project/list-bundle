<?php
/*
 * This file is part of the Austral List Bundle package.
 *
 * (c) Austral <support@austral.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Austral\ListBundle\Column;

use Austral\ListBundle\Column\Base\Column;

/**
 * Austral Column Value.
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @final
 */
class Value extends Column
{

  /**
   * @var string
   */
  protected string $type = "value";

}