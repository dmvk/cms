<?php

namespace BackendModule;

use Model\Article;

class ArticleTrashGrid extends BaseArticleGrid
{

	protected function init($presenter)
	{
		// paginator
//		$this->paginator->itemsPerPage = 5;
		// všechny články, které jsou v koši (trashed)
		$this->model->qb->add('orderBy', 'a.createdAt DESC')
			->add('where', 'a.status = :status')
			->setParameter('status', Article::TRASHED);

		$this->addColumns();

		// publish button
//		$label = function ($item) {
//			return $item->isPublished() ? 'unpublish' : 'publish';
//		};
//		
		$link = function ($item) use ($presenter) {
			return $presenter->link('restore!', $item->id);
		};

		$this->addButton('restore', 'Obnovit', $link, 'btn');
	}

}

