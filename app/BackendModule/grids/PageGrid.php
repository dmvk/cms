<?php

namespace BackendModule;

use Moes\Grid\Grid;

class PageGrid extends Grid
{

	protected function init($presenter)
	{
		// paginator
		$this->paginator->itemsPerPage = 10;

		$this->model->qb->add('orderBy', 'a.createdAt DESC');

		// columns
		$this->addColumn('id', '#');
		$this->addColumn('title', 'Titulek');
		
		$this->addColumn('author', 'Autor')->setRenderer(function ($user) {
			return $user->email;
		});
		
		$this->addColumn('slug', 'Adresa');

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
