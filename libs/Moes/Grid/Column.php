<?php

namespace Moes\Grid;

use Nette;

class Column extends Nette\Application\UI\PresenterComponent
{

	private $label;
	private $renderer;

	public function getLabel()
	{
		return $this->label;
	}

	public function setLabel($label)
	{
		$this->label = $label;
	}

	public function setRenderer($renderer)
	{
		if (is_callable($renderer)) {
			$this->renderer = $renderer;
		} else {
			throw new Nette\InvalidArgumentException();
		}
		
		return $this;
	}

	public function renderCell($item)
	{
		if ($this->renderer === NULL) {
			echo $item->{$this->name};
		} else {
			echo call_user_func($this->renderer, $item->{$this->name});
		}
	}

}