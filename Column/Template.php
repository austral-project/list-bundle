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
 * Austral Column Template.
 * @template T of object
 *
 * @extends Column<T>
 *
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @final
 */
class Template extends Column
{

  /**
   * @var string|null
   */
  protected ?string $type = "template";

  /**
   * @var string|null
   */
  protected ?string $templateName;

  /**
   * Choices constructor.
   *
   * @param string $fieldname
   * @param string|null $entitled
   * @param string|null $templateName
   * @param array<string, mixed> $options
   */
  public function __construct(string $fieldname, ?string $entitled = null, ?string $templateName = null, array $options = array())
  {
    parent::__construct($fieldname, $entitled, $options);
    $this->templateName = $templateName;
  }

  /**
   * Get templateName
   * @return string|null
   */
  public function getTemplateName(): ?string
  {
    return $this->templateName;
  }

  /**
   * @param string $templateName
   *
   * @return $this
   */
  public function setTemplateName(string $templateName): self
  {
    $this->templateName = $templateName;
    return $this;
  }

}