<?php

namespace FrontendModule\Comment;

use Nette\Application\UI;

class Control extends UI\Control
{

	private $repository;

	public function __construct($repository)
	{
		$this->repository = $repository;
	}

	public function render($commentGroup)
	{
		if ($commentGroup == NULL) {
			$this->template->empty = TRUE;
		} else {
			$this->template->comments = $group->comments;
		}

		$this->template->setFile(__DIR__ . '/group.latte');
		$this->template->render();
	}

	protected function createComponentComment()
	{
		return new UI\Multiplier(function ($comment) {
					return new Comment($comment);
				});
	}

	protected function createComponentCommentForm()
	{
		return new Form($this->repository);
	}

}
