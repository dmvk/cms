<?php

/**
 * Odlehčená verze Gridita od Honzy Marka 
 */

namespace Moes\Grid;

use Moes,
    Nette;

/**
 * @property Moes\Components\Grid\ButtonsContainer $actions
 * @property Nette\Utils\Paginator $paginator
 */
class Grid extends Nette\Application\UI\Control
{

	/**
	 * @var Model
	 */
	protected $model;

	/**
	 * @var Nette\Utils\Paginator
	 */
	private $paginator;

	public function __construct(\Doctrine\ORM\EntityRepository $repository)
	{
		parent::__construct();

		$this->model = new Model($repository);

		// @todo ButtonContainer pro akce a toolbar
		// containery
		$this->addComponent(new Nette\ComponentModel\Container(), 'actions');
		$this->addComponent(new Nette\ComponentModel\Container(), 'columns');
	}

	protected function attached($presenter)
	{
		parent::attached($presenter);

		if (method_exists($this, 'init')) {
			$this->init($presenter);
		}
	}

	public function addButton($name, $label, $link, $class)
	{
		$button = new Button($this['actions'], $name);

		$button->label = $label;
		$button->link = $link;
		$button->class = $class;

		return $button;
	}

	public function addColumn($name, $label)
	{
		// @todo presunout do ColumnContaineru
		$column = new Column($this['columns'], $name);

		$column->label = $label;

		return $column;
	}

	/**
	 * @return ActionsContainer
	 */
	public function getActions()
	{
		return $this['actions'];
	}

	public function hasActions()
	{
		return count($this['actions']->components) > 0;
	}

	/**
	 * @return Nette\Utils\Paginator
	 */
	public function getPaginator()
	{
		if ($this->paginator === NULL)
			$this->paginator = $this['paginator']->paginator;

		return $this->paginator;
	}

	public function hasPaginator()
	{
		return $this->paginator !== NULL;
	}

	public function render()
	{
		if ($this->hasPaginator()) {
			$this->model->paginator = $this->paginator;
		}

		$this->template->model = $this->model;

		$this->template->setFile(__DIR__ . '/templates/grid.latte');
		$this->template->render();
	}

	public function createComponentPaginator()
	{
		return new VisualPaginator();
	}

}