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
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\IdentityTranslator;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * ListMapper.
 * @template T of object
 *
 * @extends MapperElement<T>
 *
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
   * @var EntityManagerInterface<T>|null
   */
  protected ?EntityManagerInterface $entityManager = null;

  /**
   * @var string|null
   */
  protected ?string $title = null;

  /**
   * @var string|null
   */
  protected ?string $subTitle = null;

  /**
   * @var array<string, Section<T>>
   */
  protected array $sections = array();

  /**
   * @var array<string, ColumnActionInterface<T>>
   */
  protected array $actions = array();

  /**
   * @var array<string, string>
   */
  protected array $translateParameters = array();

  /**
   * @var array<int, ColumnActionInterface<T>>
   */
  protected array $columnsAction = array();

  /**
   * @var int
   */
  protected int $maxResult = 100;

  /**
   * Mapper constructor.
   */
  public function __construct(
    #[Autowire(service: 'router')] protected RouterInterface $router,
    #[Autowire(service: 'event_dispatcher')] protected EventDispatcherInterface $dispatcher,
    #[Autowire(service: 'translator')] protected TranslatorInterface $translate)
  {
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
   * @return TranslatorInterface
   */
  public function getTranslate(): TranslatorInterface
  {
    return $this->translate;
  }

  /**
   * @param array<string, string> $translateParameters
   *
   * @return $this
   */
  public function setTranslateParameters(array $translateParameters): self
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
  public function addTranslateParameters(string $key, string $value): self
  {
    $this->translateParameters[$key] = $value;
    return $this;
  }

  /**
   * @return array<string, string>
   */
  public function getTranslateParameters(): array
  {
    return $this->translateParameters;
  }

  /**
   * @return array<string, Section<T>>
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
   * @param DataHydrateInterface<T>|null $dataHydrate
   * @return Section<T>
   */
  public function getSection(string $sectionKey, ?DataHydrateInterface $dataHydrate = null): Section
  {
    if(!$this->sectionExist($sectionKey))
    {
      /** @var Section<T> $section */
      $section = new Section($this, $sectionKey);
      $this->sections[$sectionKey] = $section;
      $this->sections[$sectionKey]->setTranslateDomain($this->getTranslateDomain());
      if($this->dataHydrate)
      {
        $this->sections[$sectionKey]->setDataHydrate($this->dataHydrate->subDataHydrate($sectionKey, $dataHydrate));
      }
    }
    return $this->sections[$sectionKey];
  }

  /**
   * generate
   *
   * @return void
   */
  public function generate(): void
  {
    if(!$this->sections && $this->dataHydrate)
    {
      $section = $this->getSection("default", $this->dataHydrate);
      $section->setDataHydrate($this->dataHydrate->subDataHydrate("default", $this->dataHydrate));
    }

    /** @var Section<T> $section */
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
      if(!$section->getObjects() && !$section->getObjectInitialise())
      {
        $dataHydrate = $section->getDataHydrate();
        if($dataHydrate)
        {
          $section->setCountAll($dataHydrate->countAll())
            ->setObjects($dataHydrate->paginatorObjects())
            ->setCountPaginator($dataHydrate->paginatorCount());
        }
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
   * @param ColumnActionInterface<T> $action
   *
   * @return $this
   */
  public function addColumnAction(ColumnActionInterface $action): self
  {
    $this->columnsAction[] = $action;
    return $this;
  }

  /**
   * @param list<ColumnActionInterface<T>> $actions
   *
   * @return $this
   */
  public function addColumnActions(array $actions): self
  {
    foreach ($actions as $action)
    {
      $this->addColumnAction($action);
    }
    return $this;
  }

  /**
   * @param ColumnActionInterface<T> $action
   *
   * @return $this
   */
  public function addAction(ColumnActionInterface $action, ?int $position = null): self
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
   * @return array<string, ColumnActionInterface<T>>
   */
  public function actions(): array
  {
    return $this->actions;
  }


}