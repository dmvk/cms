<?php

namespace FrontendModule\Comment;

use Nette;
use Nette\Application\ForbiddenRequestException;
use Nette\Application\UI;
use Nette\Application\UI\Form;
use Nette\Security\User;
use Model\CommentGroup;
use Model\CommentRepository;

class Control extends UI\Control
{

	private $repository;

	/**
	 * @var CommentGroup
	 */
	private $group;

	/**
	 * @var User
	 */
	private $user;

	public function __construct(CommentRepository $repository, User $user)
	{
		parent::__construct();
		$this->repository = $repository;
		$this->user = $user;
	}

	private function loadComment($id)
	{
		$comment = $this->repository->find($id);

		if (!$comment) {
			throw new Nette\Application\BadRequestException();
		}

		return $comment;
	}

	public function setGroup(CommentGroup $group)
	{
		$this->group = $group;
	}

	public function handleLike($id)
	{
		$this->repository->like($this->loadComment($id));
		$this->flashMessage("Děkujeme za Váš hlas.", "info");
		$this->afterHandle();
	}

	public function handleDislike($id)
	{
		$this->repository->dislike($this->loadComment($id));
		$this->flashMessage("Děkujeme za Váš hlas.", "info");
		$this->afterHandle();
	}

	public function handleDisapprove($id)
	{
		$this->repository->disapprove($this->loadComment($id));
		$this->afterHandle();
	}

	public function handleDelete($id)
	{
		$this->repository->delete($this->loadComment($id));
		$this->flashMessage("Komentář byl odstraněn.", "error");
		$this->afterHandle();
	}

	private function afterHandle()
	{
		if ($this->presenter->isAjax()) {
			$this->invalidateControl();
		} else {
			$this->redirect("this");
		}
	}

	public function render()
	{
		if (!$this->group) {
			throw new Nette\InvalidStateException("Comment group has to be set");
		}

		$this->template->allowed = $this->user->isLoggedIn();
		$this->template->isAdmin = $this->user->isInRole("admin");

		$this->template->comments = $this->group->comments;

		$this->template->setFile(__DIR__ . '/template.latte');
		$this->template->render();
	}

	protected function createComponentCommentForm()
	{
		$form = new Form();

		$form->addTextArea('text', 'Text')
			->setRequired();

		$form->addSubmit('save');

		$form->onSuccess[] = callback($this, "processCommentForm");

		return $form;
	}

	public function processCommentForm($form)
	{
		if (FALSE) {
			throw new ForbiddenRequestException();
		}
		$comment = $this->repository->createNew((array) $form->values);

		$comment->group = $this->group;
		$comment->author = $this->user->identity;

		$this->repository->save($comment);

		$this->flashMessage('Děkujeme za Váś názor.', 'success');
		$this->afterHandle();
	}

}
