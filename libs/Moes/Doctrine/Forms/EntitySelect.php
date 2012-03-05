<?php

namespace Moes\Doctrine\Forms;

/**
 * EntitySelect
 *
 * @author Jan Marek
 */
class EntitySelect extends \Nette\Forms\Controls\SelectBox
{

	private $entities = array();
	private $id;
	private $name;

	public function __construct($label = NULL, array $entities = NULL, $id = 'id', $name = 'name')
	{
		$this->entities = $entities;
		$this->id = $id;
		$this->name = $name;

		parent::__construct($label);

		if ($entities !== NULL) {
			$this->setItems($entities);
		}
	}

	public function setItems(array $entities, $useKeys = TRUE)
	{
		$items = array();

		foreach ($entities as $item)
			$items[$item->{$this->id}] = $item->{$this->name};

		parent::setItems($items);
	}

	public function setValue($value)
	{
		if ($value instanceof \Moes\Model\BaseEntity) {
			$value = $value->{$this->id};
		}
		parent::setValue($value);
	}

	public function getValue()
	{
		$back = debug_backtrace();
		if (isset($back[1]["function"]) && isset($back[1]["class"]) && $back[1]["function"] === "getControl" && $back[1]["class"] === "Nette\Forms\Controls\SelectBox") {
			return parent::getValue();
		}
		
		$val = parent::getValue();

		foreach ($this->entities as $item) {
			if ($item->{$this->id} == $val) {
				return $item;
			}
		}
		
		return NULL;
	}

}
