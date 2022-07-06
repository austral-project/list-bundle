<?php
/*
 * This file is part of the Austral List Bundle package.
 *
 * (c) Austral <support@austral.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Austral\ListBundle\Model;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Austral Module Interface.
 * @author Matthieu Beurel <matthieu@austral.dev>
 * @abstract
 */
interface ModuleInterface
{

  /**
   * @param string|null $actionKey
   * @param array $parameters
   * @param int $referenceType
   *
   * @return mixed|null
   */
  public function generateUrl(string $actionKey = null, array $parameters = array(), int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH);

  /**
   * @param string $actionKey
   *
   * @return bool
   */
  public function isGranted(string $actionKey = "default"): bool;

  /**
   * @return string
   */
  public function translateSingular(): string;

  /**
   * @return string
   */
  public function translatePlural(): string;

  /**
   * @return string
   */
  public function translateKey(): string;

  /**
   * @return string
   */
  public function translateGenre(): string;

}