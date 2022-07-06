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
 * Austral Column Date.
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @final
 */
class Date extends Column
{

  /**
   * @var string
   */
  protected string $type = "date";

  /**
   * @var string
   */
  protected string $format;

  /**
   * Choices constructor.
   *
   * @param string $fieldname
   * @param string|null $entitled
   * @param string $format
   * @param array $options
   */
  public function __construct(string $fieldname, ?string $entitled = null, string $format = "Y-m-d", array $options = array())
  {
    parent::__construct($fieldname, $entitled, $options);
    $this->format = $format;
  }

  /**
   * Get format
   * @return string
   */
  public function getFormat(): string
  {
    return $this->format;
  }

  /**
   * Set format
   *
   * @param string $format
   *
   * @return $this
   */
  public function setFormat(string $format): Date
  {
    $this->format = $format;
    return $this;
  }

}