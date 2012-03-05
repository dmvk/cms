<?php

namespace FrontendModule\Comment;

use Nette\Application\UI;

class Comment extends UI\Control
{

	private $comment;

	public function __construct($comment)
	{
		$this->comment = $comment;
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/group.latte');
		$this->template->render();
	}

}
