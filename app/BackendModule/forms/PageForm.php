<?php

namespace BackendModule;

use Moes\Security\Identity;

class PageForm extends \Moes\Doctrine\EntityForm
{

	/**
	 * @var Identity
	 */
	private $author;

	public function __construct($repository, Identity $author)
	{
		parent::__construct($repository);
		$this->author = $author;
	}

	protected function init()
	{
		$this->addText('title', 'Title')->setRequired();
		$this->addText('slug', 'Slug');
		$this->addTextArea('text', 'Text')->setRequired();

		$this->addSubmit('save', 'Uložit')->onClick[] = callback($this, 'saveClicked');
		$this->addSubmit('cancel', 'Zrušit změny')->setValidationScope(FALSE)->onClick[] = callback($this, 'cancelClicked');
	}

	public function saveClicked($button)
	{
		$form = $button->form;

		if ($form->entity) {
			// update
			$entity = $form->entity;
			$this->repository->setData($entity, $form->values);

			$this->presenter->flashMessage('Stránka byla upravena', 'success');
			$this->presenter->logUpdateAction($entity->title);
		} else {
			// create
			$entity = $this->repository->createNew((array) $form->values);
			$entity->author = $this->author;

			$this->presenter->flashMessage('Stránka byla úspěšně vytvořena', 'success');
			$this->presenter->logCreateAction($entity->title);
		}

		$this->repository->save($entity);
		$this->presenter->redirect('default');
	}

	public function cancelClicked($button)
	{
		$this->presenter->flashMessage('Změny byly zrušeny', 'error');
		$this->presenter->redirect('default');
	}

}