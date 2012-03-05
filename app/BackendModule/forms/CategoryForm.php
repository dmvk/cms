<?php

namespace BackendModule;

class CategoryForm extends \Moes\Doctrine\EntityForm
{

	protected function init()
	{
		$this->addText('name', 'Název')->setRequired();

		$this->addTextArea('description', 'Popis');

		$this->addSubmit('save', 'Save');
	}

	public function process($form)
	{
		if ($form->entity) {
			$this->presenter->flashMessage('Site has been updated', 'success');
			$entity = $form->entity;
		} else {
			$this->presenter->flashMessage('Site has been created', 'success');
			$entity = $this->repository->createNew();
		}

		$this->repository->setData($entity, $form->values);
		$this->repository->save($entity);
		
		$this->presenter->redirect('default');
	}

}