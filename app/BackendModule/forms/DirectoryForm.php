<?php

namespace BackendModule;

class DirectoryForm extends \Moes\Form\BaseForm
{

	private $path;

	public function setPath($path)
	{
		$this->path = $path;
	}

	protected function init()
	{
		$this->addText('name', 'Directory name')
			->addRule(static::PATTERN, "Pouze alfanumerické znaky", "\w+")
			->setRequired();

		$this->addSubmit('create', 'Create');
	}

	public function process(DirectoryForm $form)
	{
		mkdir($this->path . '/' . $form['name']->value);
		$this->presenter->redirect('this');
	}

}