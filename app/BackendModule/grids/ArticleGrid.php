<?php

namespace BackendModule;

use Model\Article;

class ArticleGrid extends BaseArticleGrid
{

	protected function init($presenter)
	{
		// paginator
//		$this->paginator->itemsPerPage = 5;

		// všechny články, které nejsou v koši (published and draft)
		$this->model->qb->add("orderBy", "a.createdAt DESC")
			->add("where", "a.status <> :status")
			->setParameter("status", Article::TRASHED);
		
		$this->addColumns();

		// publish button
		$link =  function ($item) use ($presenter) {
			return $item->isPublished() ? $presenter->link("unpublish!", $item->id) : $presenter->link("publish!", $item->id);
		};
		
		$label = function ($item) use ($presenter) {
			return $item->isPublished() ? "Odpublikovat" : "Publikovat";
		};

		$this->addButton("publish", $label, $link, "btn");

		// edit button
		$link = function ($item) use ($presenter) {
			return $presenter->link("edit", $item->id);
		};

		$this->addButton("edit", "Upravit", $link, "btn btn-success");

		// delete button
		$link = function ($item) use ($presenter) {
				return $presenter->link("trash!", $item->id);
		};
		
		$this->addButton("trash", "Přesunout do koše", $link, "btn btn-danger");
	}

}

