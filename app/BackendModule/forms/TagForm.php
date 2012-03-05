<?php

namespace BackendModule;

use Nette\Forms\Controls\SubmitButton;

class TagForm extends \Moes\Form\EntityForm
{

	protected function init()
	{
		$replicator = $this->addDynamic('tags', function($container) {
					$container->addText('name', 'Name')
							->setRequired();

					$add = $container->addSubmit('add', 'Add another tag')
							->setValidationScope(false);

					$add->onClick[] = function ($button) {
								$button->parent->parent->createOne();
							};

					$add->getControlPrototype()->class = 'btn success';

					$remove = $container->addSubmit('remove', 'Remove tag')
							->setValidationScope(false);

					$remove->onClick[] = function ($button) {
								$replicator = $button->parent->parent;
								$replicator->remove($button->parent, TRUE);
							};

					$remove->getControlPrototype()->class = 'btn error';
				}, 1);

		$submit = $this->addSubmit('save', 'Create tags');
		$submit->onClick[] = callback($this, 'process');
		$submit->getControlPrototype()->setClass('btn primary');
	}

	public function process(SubmitButton $button)
	{
		$service = $this->presenter->context->tagService;
		
		foreach ($button->form['tags']->values as $tag) {
			$entity = $service->createNew();
			$service->setData($entity, $tag);
			$service->persist($entity);
		}

		$service->flush();

		$this->presenter->flashMessage('Tags have been created', 'success');
		$this->presenter->redirect('default');
	}

}