<?php

namespace BackendModule;

use Moes\Security\Identity;

class ArticleForm extends \Moes\Doctrine\EntityForm
{

	/**
	 * @var Identity
	 */
	private $author;

	/**
	 * @var array
	 */
	private $categories;

	public function __construct($repository, Identity $author, $categories)
	{
		parent::__construct($repository);
		$this->author = $author;
		$this->categories = $categories;
	}

	protected function init()
	{
		$this->addText("title", "Titulek")->setRequired();
		$this->addText("slug", "Adresa článku");
		$this->addEntitySelect("category", "Kategorie", $this->categories)->setPrompt("------");
		$this->addTextArea("introduction", "Úvod");
		$this->addTextArea("text", "Článek")->setRequired();

		// tlacitka
		$this->addSubmit("save", "Uložit")->onClick[] = callback($this, "saveClicked");
		$this->addSubmit("cancel", "Zrušit změny")->setValidationScope(FALSE)->onClick[] = callback($this, "cancelClicked");

		$this->addSubmit("publish", "Publikovat")->onClick[] = callback($this, "publishClicked");
	}

	public function saveClicked($button)
	{
		$form = $button->form;

		if ($form->entity) {
			// update
			$entity = $form->entity;
			$this->repository->setData($entity, $form->values);

			$this->presenter->logUpdateAction($entity->title);
			$this->presenter->flashMessage("Článek byl upraven", "success");
		} else {
			// create
			$entity = $this->repository->createNew((array) $form->values);
			$entity->author = $this->author;

			$this->presenter->logCreateAction($entity->title);
			$this->presenter->flashMessage("Článek byl úspěšně vytvořen", "success");
		}

		$this->repository->save($entity);
		$this->presenter->redirect("default");
	}

	public function cancelClicked($button)
	{
		$this->presenter->flashMessage("Změny byly zrušeny", "error");
		$this->presenter->redirect("default");
	}

}