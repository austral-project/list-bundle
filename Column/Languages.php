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

use Austral\EntityBundle\Entity\EntityInterface;
use Austral\ListBundle\Column\Base\Column;
use Austral\SeoBundle\Entity\Traits\UrlParameterTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Austral Column Choice.
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @final
 */
class Languages extends Column
{

  /**
   * @var string
   */
  protected string $type = "languages";

  /**
   * @var array
   */
  protected array $values = array();

  /**
   * Choices constructor.
   *
   * @param string|null $entitled
   * @param array $options
   */
  public function __construct(?string $entitled = null, array $options = array())
  {
    parent::__construct("translates", $entitled, $options);
  }

  /**
   * @param OptionsResolver $resolver
   */
  protected function configureOptions(OptionsResolver $resolver)
  {
    parent::configureOptions($resolver);
  }

  /**
   * @param EntityInterface|UrlParameterTrait $object
   *
   * @return array|null
   */
  public function getter($object)
  {
    $languages = array();
    foreach ($object->getTranslates() as  $translate)
    {
      $languages[$translate->getLanguage()] = $translate;
    }
    ksort($languages);
    return $languages;
  }

}