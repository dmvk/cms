<?php

namespace BackendModule;

use Moes\Grid\Grid;

class CategoryGrid extends Grid
{

	protected function init($presenter)
	{
		// paginator
		$this->paginator->itemsPerPage = 5;

		// columns
		$this->addColumn('id', '#');
		$this->addColumn('name', 'Název');

		$this->addColumn('articles', 'Počet článků')->setRenderer(function ($articles) {
			return $articles->count();
		});

		// edit button
		$link = function ($item) use ($presenter) {
			return $presenter->link('edit', $item->id);
		};
		
		$this->addButton('edit', 'Upravit', $link, 'btn btn-success');

		// delete button
		$link = function ($item) use ($presenter) {
			return $presenter->link('delete!', $item->id);
		};
		
		$this->addButton('delete', 'Smazat', $link, 'btn btn-danger');
	}

}
