<?php
/*
 * This file is part of the Austral List Bundle package.
 *
 * (c) Austral <support@austral.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Austral\ListBundle\Mapper;

use Austral\EntityBundle\EntityManager\EntityManagerInterface;
use Austral\ListBundle\Column\Interfaces\ColumnActionInterface;
use Austral\ListBundle\Column\Interfaces\ColumnInterface;
use Austral\ListBundle\Column\Interfaces\ColumnWithPathInterface;

use Austral\ListBundle\DataHydrate\DataHydrateInterface;
use Austral\ListBundle\Mapper\Base\MapperElement;
use Austral\ListBundle\Section\Section;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\IdentityTranslator;

/**
 * Mapper.
 * @author Matthieu Beurel <matthieu@austral.dev>
 */
class ListMapper extends MapperElement
{

  const SIZE_AUTO = 'auto';
  const SIZE_COL_1 = 'col--1';
  const SIZE_COL_2 = 'col--2';
  const SIZE_COL_3 = 'col--3';
  const SIZE_COL_4 = 'col--4';
  const SIZE_COL_5 = 'col--5';
  const SIZE_COL_6 = 'col--6';
  const SIZE_COL_7 = 'col--7';
  const SIZE_COL_8 = 'col--8';
  const SIZE_COL_9 = 'col--9';
  const SIZE_COL_10 = 'col--10';
  const SIZE_COL_11 = 'col--11';
  const SIZE_COL_12 = 'col--12';

  const COL_ALIGN_LEFT = "col--align-left";
  const COL_ALIGN_CENTER = "col--align-center";
  const COL_ALIGN_RIGHT = "col--align-right";

  const PAGINATION_DEFAULT = "default";
  # Not implement
  #const PAGINATION_SCROLL = "scroll";

  /**
   * @var RouterInterface
   */
  protected RouterInterface $router;

  /**
   * @var object|IdentityTranslator|null
   */
  protected $translate = null;

  /**
   * @var EventDispatcherInterface
   */
  protected EventDispatcherInterface $dispatcher;

  /**
   * @var EntityManagerInterface
   */
  protected EntityManagerInterface $entityManager;

  /**
   * @var string|null
   */
  protected ?string $title = null;

  /**
   * @var string|null
   */
  protected ?string $subTitle = null;

  /**
   * @var array
   */
  protected array $sections = array();

  /**
   * @var array
   */
  protected array $actions = array();

  /**
   * @var array
   */
  protected array $translateParameters = array();

  /**
   * @var array
   */
  protected array $columnsAction = array();

  /**
   * @var int
   */
  protected int $maxResult = 100;

  /**
   * Mapper constructor.
   */
  public function __construct(RouterInterface $router, EventDispatcherInterface $dispatcher, $translate)
  {
    $this->router = $router;
    $this->dispatcher = $dispatcher;
    $this->translate = $translate;
  }

  /**
   * @return EventDispatcherInterface
   */
  public function getDispatcher(): EventDispatcherInterface
  {
    return $this->dispatcher;
  }

  /**
   * @return RouterInterface
   */
  public function getRouter(): RouterInterface
  {
    return $this->router;
  }

  /**
   * @return object|IdentityTranslator|null
   */
  public function getTranslate()
  {
    return $this->translate;
  }

  /**
   * @param $translateParameters
   *
   * @return $this
   */
  public function setTranslateParameters($translateParameters): ListMapper
  {
    $this->translateParameters = $translateParameters;
    return $this;
  }

  /**
   * @param string $key
   * @param string $value
   *
   * @return $this
   */
  public function addTranslateParameters(string $key, string $value): ListMapper
  {
    $this->translateParameters[$key] = $value;
    return $this;
  }

  /**
   * @return array
   */
  public function getTranslateParameters(): array
  {
    return $this->translateParameters;
  }

  /**
   * @return array
   */
  public function getSections(): array
  {
    return $this->sections;
  }

  /**
   * @param string $sectionKey
   *
   * @return bool
   */
  public function sectionExist(string $sectionKey): bool
  {
    return array_key_exists($sectionKey, $this->sections);
  }

  /**
   * @param string $sectionKey
   * @param DataHydrateInterface|null $dataHydrate
   * @return Section
   */
  public function getSection(string $sectionKey, ?DataHydrateInterface $dataHydrate = null): Section
  {
    if(!$this->sectionExist($sectionKey))
    {
      $this->sections[$sectionKey] = new Section($this, $sectionKey);
      $this->sections[$sectionKey]->setTranslateDomain($this->getTranslateDomain());
      $this->sections[$sectionKey]->setDataHydrate($this->dataHydrate->subDataHydrate($sectionKey, $dataHydrate));
    }
    return $this->sections[$sectionKey];
  }

  /**
   */
  public function generate()
  {
    if(!$this->sections)
    {
      $this->getSection("default", $this->dataHydrate);
      $this->sections["default"]->setDataHydrate($this->dataHydrate->subDataHydrate("default", $this->dataHydrate));
    }

    /** @var Section $section */
    foreach($this->sections as $section)
    {
      if($this->headerColumns)
      {
        $section->addColumns($this->headerColumns);
      }
      if($this->filter && !$section->getFilter())
      {
        $section->setFilter($this->filter);
      }
      if(!$section->getObjects())
      {
        $section->setCountAll($section->getDataHydrate()->countAll())
          ->setObjects($section->getDataHydrate()->paginatorObjects())
          ->setCountPaginator($section->getDataHydrate()->paginatorCount());

      }
      $section->generateRows();
      if(!$section->columnActionsDefined())
      {
        foreach ($this->columnsAction as $action)
        {
          $section->addColumnAction($action);
        }
      }
    }
  }

  /**
   * @param ColumnActionInterface $action
   *
   * @return ListMapper
   */
  public function addColumnAction(ColumnActionInterface $action): ListMapper
  {
    $this->columnsAction[] = $action;
    return $this;
  }

  /**
   * @param ColumnActionInterface|ColumnWithPathInterface|ColumnInterface $action
   *
   * @return $this
   */
  public function addAction(ColumnActionInterface $action, ?int $position = null): ListMapper
  {
    if(!$action->translateDomain())
    {
      $action->setTranslateDomain($this->translateDomain);
    }
    $position = $position > 0 ? $position : count($this->actions)+1;
    $this->actions["{$position}-{$action->keyname()}"] = $action;
    ksort($this->actions);
    return $this;
  }

  /**
   * @return array
   */
  public function actions(): array
  {
    return $this->actions;
  }


}