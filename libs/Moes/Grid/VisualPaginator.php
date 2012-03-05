<?php

namespace Moes\Grid;

use Nette;

/**
 * @author David Grudl
 */
class VisualPaginator extends Nette\Application\UI\Control
{

	/** @var Nette\Utils\Paginator */
	private $paginator;

	/** @persistent */
	public $page = 1;

	public function __construct()
	{
		parent::__construct();
		$this->paginator = new \Nette\Utils\Paginator();
	}

	public function getPaginator()
	{
		return $this->paginator;
	}

	public function render()
	{
		$paginator = $this->paginator;
		$page = $paginator->page;
		if ($paginator->pageCount < 2) {
			$steps = array($page);
		} else {
			$arr = range(max($paginator->firstPage, $page - 3), min($paginator->lastPage, $page + 3));
			$count = 4;
			$quotient = ($paginator->pageCount - 1) / $count;
			for ($i = 0; $i <= $count; $i++) {
				$arr[] = round($quotient * $i) + $paginator->firstPage;
			}
			sort($arr);
			$steps = array_values(array_unique($arr));
		}

		$this->template->steps = $steps;
		$this->template->paginator = $paginator;

		$this->template->setFile(__DIR__ . '/templates/paginator.latte');
		$this->template->render();
	}

	public function loadState(array $params)
	{
		parent::loadState($params);
		$this->paginator->page = $this->page;
	}

}