<?php

namespace FrontendModule;

use Nette;
use Nette\Application\ForbiddenRequestException;
use Nette\Application\UI;
use Nette\Application\UI\Form;
use Nette\Security\User;
use Model\CommentGroup;
use Model\CommentRepository;

class CommentControl extends UI\Control
{

	/**
	 * @var CommentRepository
	 */
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
		$this->checkPermission("vote");
		$this->repository->like($this->loadComment($id));
		$this->flashMessage("Děkujeme za Váš hlas.", "info");
		$this->finishSignal();
	}

	public function handleDislike($id)
	{
		$this->checkPermission("vote");
		$this->repository->dislike($this->loadComment($id));
		$this->flashMessage("Děkujeme za Váš hlas.", "info");
		$this->finishSignal();
	}

	public function handleDisapprove($id)
	{
		$this->checkPermission("edit");
		$this->repository->disapprove($this->loadComment($id));
		$this->finishSignal();
	}

	public function handleDelete($id)
	{
		$this->checkPermission("edit");
		$this->repository->delete($this->loadComment($id));
		$this->flashMessage("Komentář byl odstraněn.", "error");
		$this->finishSignal();
	}

	public function render()
	{
		if (!$this->group) {
			throw new Nette\InvalidStateException("You have to set CommentGroup first ...");
		}

		// permissions
		$this->template->showAddForm = $this->user->isAllowed("comment", "add");
		$this->template->showVoteButtons = $this->user->isAllowed("comment", "vote");
		$this->template->showEditButtons = $this->user->isAllowed("comment", "edit");

		$this->template->comments = $this->group->comments;

		$this->template->setFile(__DIR__ . '/template.latte');
		$this->template->render();
	}

	protected function createComponentCommentForm()
	{
		$form = new Form();

		$form->addTextArea('text', 'Text')->setRequired();
		$form->addSubmit('save');

		$form->onSuccess[] = callback($this, "processCommentForm");

		return $form;
	}

	public function processCommentForm($form)
	{
		$this->checkPermission("add");

		$comment = $this->repository->createNew((array) $form->values);
		$comment->group = $this->group;
		$comment->author = $this->user->identity;

		$this->repository->save($comment);

		$this->flashMessage('Děkujeme za Váś názor.', 'success');
		$this->finishSignal();
	}

	private function checkPermission($privilege)
	{
		if (!$this->user->isAllowed("comment", $privilege)) {
			throw new ForbiddenRequestException();
		}
	}

	private function finishSignal()
	{
		if ($this->presenter->isAjax()) {
			$this->invalidateControl();
		} else {
			$this->redirect("this");
		}
	}

}
