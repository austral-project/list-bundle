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

use Austral\ListBundle\Column\Base\ColumnWithPath;
use Austral\ToolsBundle\AustralTools;

/**
 * Austral Column Choice.
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @final
 */
class Choices extends ColumnWithPath
{

  /**
   * @var string
   */
  protected string $type = "choices";

  /**
   * @var array
   */
  protected array $choices = array();

  /**
   * @var bool
   */
  protected bool $isGranted = true;

  /**
   * Choices constructor.
   *
   * @param string $fieldname
   * @param string|null $entitled
   * @param array $choices
   * @param string|null $path
   * @param bool $isGranted
   * @param array $options
   */
  public function __construct(string $fieldname, ?string $entitled = null, array $choices = array(), string $path = null, bool $isGranted = true, array $options = array())
  {
    parent::__construct($fieldname, $entitled, $path, $options);
    $this->choices = $choices;
    $this->isGranted = $isGranted;
  }

  /**
   * Get choices
   * @return array
   */
  public function getChoices(): array
  {
    return $this->choices;
  }

  /**
   * Set choices
   *
   * @param array $choices
   *
   * @return Choices
   */
  public function setChoices(array $choices): Choices
  {
    $this->choices = $choices;
    return $this;
  }

  /**
   * @return bool
   */
  public function isGranted(): bool
  {
    return $this->isGranted;
  }

  /**
   * @param string $value
   *
   * @return false|int|string
   */
  public function getEntitledByChoiceValue(string $value)
  {
    $return = null;
    if(array_key_exists($value, $this->choices))
    {
      $choice = $this->choices[$value];
      if(is_array($choice))
      {
        $return = $choice["entitled"];
      }
      else
      {
        $return = $choice;
      }
    }

    return $return;
  }

  /**
   * @param array $pathAttribute
   *
   * @return array
   */
  public function actions(array $pathAttribute = array()): array
  {
    $actions = array();
    $this->pathAttributes = array_replace($this->pathAttributes, $pathAttribute);
    $this->pathAttributes['__fieldname__'] = $this->fieldname;
    foreach($this->choices as $value => $parameters)
    {
      if(is_array($parameters))
      {
        $entilted = AustralTools::getValueByKey($parameters, "entitled", null);
        $picto = AustralTools::getValueByKey($parameters, "picto", null);
        $styles = AustralTools::getValueByKey($parameters, "styles", null);
      }
      else
      {
        $entilted = $parameters;
        $picto = "";
        $styles = null;
      }
      $this->pathAttributes['__value__'] = $value;
      $action = new Action("change.{$value}", $entilted, $this->path(), $picto, array(
          "translateDomain"             => $this->translateDomain(),
          "data-url"                    =>  true,
          "class"                       =>  "{$value}-value",
          "attr"  =>  array(
            "data-click-actions"        =>  "refresh-element",
            "data-reload-elements"      =>  "#{$this->id}",
            "style"                     =>  $styles
          )
        )
      );
      $action->setValue($value);
      $actions[$value] = $action;
    }
    return $actions;
  }

}