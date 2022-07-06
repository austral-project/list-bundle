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
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Austral Column Switch.
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @final
 */
class SwitchValue extends ColumnWithPath
{

  /**
   * @var string
   */
  protected string $type = "switch";

  /**
   * @var bool
   */
  protected bool $isGranted = true;

  /**
   * @var array
   */
  protected $switchOptions = array();

  /**
   * @var mixed
   */
  protected $heads;

  /**
   * @var mixed
   */
  protected $tails;

  /**
   * Choices constructor.
   *
   * @param string $fieldname
   * @param string|null $entitled
   * @param mixed $heads
   * @param mixed $tails
   * @param string|null $path
   * @param bool $isGranted
   * @param array $options
   */
  public function __construct(string $fieldname, ?string $entitled = null, $heads = 0, $tails = 1, string $path = null, bool $isGranted = true, array $options = array())
  {
    parent::__construct($fieldname, $entitled, $path, $options);
    $this->isGranted = $isGranted;
    $this->heads = $heads;
    $this->tails = $tails;
    $this->switchOptions = $this->options['switch-options'];
  }

  /**
   * @param OptionsResolver $resolver
   */
  protected function configureOptions(OptionsResolver $resolver)
  {
    parent::configureOptions($resolver);
    $resolver->setDefault("viewEntitled", false);
    $resolver->setDefault('switch-options', function (OptionsResolver $resolverChild) {
      $resolverChild->setDefaults(array(
          "title"                 =>  null,
          "summary"               =>  null,
        )
      );
      $resolverChild->setDefault("pictos", function (OptionsResolver $resolverSubChild) {
        $resolverSubChild->setDefaults(array(
            "enabled"                 =>  null,
            "disabled"                =>  null,
          )
        );
        $resolverSubChild->setAllowedTypes('enabled', array('string', "null"));
        $resolverSubChild->setAllowedTypes('disabled', array('string', "null"));
      });
      $resolverChild->setDefault("button-colors", function (OptionsResolver $resolverSubChild) {
        $resolverSubChild->setDefaults(array(
            "enabled"                 =>  "",
            "disabled"                =>  "",
          )
        );
        $resolverSubChild->setAllowedTypes('enabled', array('string', "null"));
        $resolverSubChild->setAllowedTypes('disabled', array('string', "null"));
      });
      $resolverChild->setDefault("fond-colors", function (OptionsResolver $resolverSubChild) {
        $resolverSubChild->setDefaults(array(
            "enabled"                 =>  "",
            "disabled"                =>  "",
          )
        );
        $resolverSubChild->setAllowedTypes('enabled', array('string', "null"));
        $resolverSubChild->setAllowedTypes('disabled', array('string', "null"));
      });
      $resolverChild->setAllowedTypes('title', array('string', "null"));
      $resolverChild->setAllowedTypes('summary', array('string', "null"));
    });
  }

  /**
   * @return bool
   */
  public function isGranted(): bool
  {
    return $this->isGranted;
  }

  /**
   * @return mixed
   */
  public function heads()
  {
    return $this->heads;
  }

  /**
   * @return mixed
   */
  public function tails()
  {
    return $this->tails;
  }

  /**
   * @return array
   */
  public function actions(): array
  {
    $actions = array();
    $this->pathAttributes['__fieldname__'] = $this->fieldname;

    $this->pathAttributes['__value__'] = $this->heads;
    $actions["heads"] = new Action("change.heads", "", $this->path(), null, array(
        "translateDomain" => $this->translateDomain(),
        "data-url"                    =>  true,
        "class"                       =>  "heads-value",
        "attr"  =>  array(
          "data-click-actions"        =>  "refresh-element",
          "data-reload-elements"      =>  "#{$this->id}",
        )
      )
    );

    $this->pathAttributes['__value__'] = $this->tails;
    $actions["tails"] = new Action("change.tails", "", $this->path(), null, array(
        "translateDomain" => $this->translateDomain(),
        "data-url"                    =>  true,
        "class"                       =>  "tails-value",
        "attr"  =>  array(
          "data-click-actions"        =>  "refresh-element",
          "data-reload-elements"      =>  "#{$this->id}",
        )
      )
    );
    return $actions;
  }

  /**
   * @return array
   */
  public function getStyles(): array
  {
    $styles = array();
    foreach($this->switchOptions['button-colors'] as $key => $color)
    {
      if($color)
      {
        $styles[] = "--switch-button-color-{$key}:{$color};";
      }
    }
    foreach($this->switchOptions['fond-colors'] as $key => $color)
    {
      if($color)
      {
        $styles[] = "--switch-fond-color-{$key}:{$color};";
      }
    }
    return $styles;
  }

  /**
   * @return array
   */
  public function switchData(): array
  {
    $data =  array();
    /**
     * @var string $key
     * @var Action $action
     */
    foreach($this->actions() as $key => $action)
    {
      $data[$key] = $action->path();
    }
    return $data;
  }

}