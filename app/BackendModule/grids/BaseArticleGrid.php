<?php

namespace BackendModule;

use Moes\Grid\Grid;

abstract class BaseArticleGrid extends Grid
{

	protected function addColumns()
	{
		// columns
		$this->addColumn('id', '#');
		$this->addColumn('title', 'Titulek');

		$this->addColumn('category', 'Kategorie')->setRenderer(function ($category) {
			if ($category === NULL) {
				return 'Uncategorized';
			}
			return $category->name;
		});

		$this->addColumn('author', 'Autor')->setRenderer(function ($user) {
			return $user->email;
		});

		$this->addColumn('status', 'Status')->setRenderer(function ($status) {
			return \Nette\Utils\Html::el('span')->class('label')->setText($status);
		});
	}

}
