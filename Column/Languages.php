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

use Austral\Contracts\Entity\EntityInterface;
use Austral\Contracts\Translate\TranslateChildInterface;
use Austral\Contracts\Translate\TranslateMasterInterface;
use Austral\ListBundle\Column\Base\Column;

/**
 * Austral Column Choice.
 * @template T of object
 *
 * @extends Column<T>
 *
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @final
 */
class Languages extends Column
{

  /**
   * @var string|null
   */
  protected ?string $type = "languages";

  /**
   * Choices constructor.
   *
   * @param string|null $entitled
   * @param array<string, mixed> $options
   */
  public function __construct(?string $entitled = null, array $options = array())
  {
    parent::__construct("translates", $entitled, $options);
  }

  /**
   * @param EntityInterface<T> $object
   *
   * @return array<string, EntityInterface<T>>|null
   */
  public function getter(EntityInterface $object): ?array
  {
    $languages = array();
    if($object instanceof TranslateMasterInterface)
    {
      /** @var TranslateChildInterface<T> $translate */
      foreach ($object->getTranslates() as  $translate)
      {
        $languages[$translate->getLanguage()] = $translate;
      }
    }
    ksort($languages);
    return $languages;
  }

}