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

use Austral\EntityBundle\Entity\EntityInterface;
use Austral\ListBundle\Column\Interfaces\ColumnActionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Austral Column Action.
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @final
 */
class Action extends ColumnWithPath implements ColumnActionInterface
{

  /**
   * @var string|null
   */
  protected ?string $picto;

  /**
   * @var string
   */
  protected string $keyname;

  /**
   * @var EntityInterface|null
   */
  protected ?EntityInterface $object = null;

  /**
   * Choices constructor.
   *
   * @param string $keyname
   * @param string $entitled
   * @param string|null $path
   * @param string|null $picto
   * @param array $options
   */
  public function __construct(string $keyname, string $entitled, string $path = null, string $picto = null, array $options = array())
  {
    parent::__construct("_action.{$keyname}", $entitled, $path, $options);
    $this->keyname = $keyname;
    $this->picto = $picto;
    $this->class .= " link";
  }

  /**
   * @param OptionsResolver $resolver
   */
  protected function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
        "data-url"            =>  false,
        "language"            =>  false,
        "id"                  =>  "",
        "class"               =>  "",
        "attr"                =>  array(),
        "choices"             =>  array(),
        "translateDomain"     =>  null,
        "translateParameters" =>  array(),
        "keybord-shortcut"    =>  null,
      )
    );
  }

  /**
   * Get keyname
   * @return string
   */
  public function keyname(): string
  {
    return $this->keyname;
  }

  /**
   * @return array
   */
  public function attr(): array
  {
    if($this->options["data-url"])
    {
      $this->attr['data-url'] = $this->path();
    }
    if($this->options["keybord-shortcut"])
    {
      $this->attr['data-keybord-shortcut'] = $this->getKeybordShortcut();
    }
    return $this->attr;
  }

  /**
   * @param string $keyname
   *
   * @return Action
   */
  public function setKeyname(string $keyname): Action
  {
    $this->keyname = $keyname;
    return $this;
  }

  /**
   * Get picto
   * @return string|null
   */
  public function picto(): ?string
  {
    return $this->picto;
  }

  /**
   * @param string|null $picto
   *
   * @return Action
   */
  public function setPicto(?string $picto): Action
  {
    $this->picto = $picto;
    return $this;
  }

  public function getKeybordShortcut()
  {
    return $this->options['keybord-shortcut'];
  }

}