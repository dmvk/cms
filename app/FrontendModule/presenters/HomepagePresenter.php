<?php

namespace FrontendModule;

class HomepagePresenter extends BasePresenter
{

	public function actionDefault()
	{
		$this->template->articles = $this->context->repository->article->findPublished();
	}

}