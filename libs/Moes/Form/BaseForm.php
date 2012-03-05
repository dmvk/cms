<?php

namespace Moes\Form;

use Nette\Utils\Html;

class BaseForm extends \Nette\Application\UI\Form
{

	public function __construct($parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);

//		if (method_exists($this, 'init'))
//			$this->init();

		if (method_exists($this, 'process'))
			$this->onSuccess[] = callback($this, 'process');

		$renderer = $this->getRenderer();
		$renderer->wrappers['controls']['container'] = NULL;
		$renderer->wrappers['label']['container'] = NULL;
		$renderer->wrappers['pair']['container'] = 'div class="control-group"';
		$renderer->wrappers['control']['container'] = 'div class="controls"';
	}

	protected function attached($presenter)
	{
		parent::attached($presenter);

		if (method_exists($this, 'init')) {
			$this->init($presenter);
		}
	}

	public function addDynamic($name, $factory, $createDefault)
	{
		$this[$name] = new Replicator($factory, $createDefault);
	}

	public function addDatePicker($name, $label = NULL, $cols = NULL, $maxLength = NULL)
	{
		return $this[$name] = new DatePicker($label, $cols, $maxLength);
	}

}