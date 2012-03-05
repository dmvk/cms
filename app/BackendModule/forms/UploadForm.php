<?php

namespace BackendModule;

class UploadForm extends \Moes\Form\BaseForm
{

	protected function init()
	{
		$this->getElementPrototype()->class('html5upload');

		$this->addUpload('files', 'Soubory')->setRequired();

		$this->addSubmit('upload')->getControlPrototype()->class('btn btn-primary');
	}

	public function process(UploadForm $form)
	{
		foreach ($form['files']->value as $file) {
			$fileName = Strings::webalize($file->name, '.');
			$file->move($this->getFullPath($this->getParam('path')) . $fileName);
		}

		$this->presenter->flashMessage('Soubor byl nahrán');
		
		if ($this->isAjax()) {
			$this->presenter->invalidateControl('browser');
		} else {
			$this->presenter->redirect('default');
		}
	}

}