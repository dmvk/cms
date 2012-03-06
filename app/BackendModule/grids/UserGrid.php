<?php

namespace BackendModule;

use Moes\Grid\Grid;
use Moes\Security\IdentityRepository;

class UserGrid extends Grid
{

	public function __construct(IdentityRepository $repository)
	{
		parent::__construct($repository);
	}
	
	protected function init($presenter)
	{
		// paginator
		$this->paginator->itemsPerPage = 5;

		// columns
		$this->addColumn('id', '#');
		$this->addColumn('email', 'E-mail');
		$this->addColumn('facebook', 'Facebook')->setRenderer(function ($fb) {
			return $fb !== NULL ? 'Ano' : 'Ne';
		});

		// edit button
		$link = function ($item) use ($presenter) {
			return $presenter->link('edit', $item->id);
		};
		
		$edit = $this->addButton('edit', 'Upravit', $link, 'btn btn-success');


		// delete button
		$link = function ($item) use ($presenter) {
			return $presenter->link('delete!', $item->id);
		};

		$this->addButton('delete', 'Smazat', $link, 'btn btn-danger');
		
		return $this;
	}

}
