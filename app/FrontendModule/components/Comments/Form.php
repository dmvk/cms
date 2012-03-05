<?php

namespace FrontendModule\Comment;

class Form extends \Moes\Doctrine\EntityForm
{

	public function init()
	{
		$this->addText('Nadpis', 'Nadpis');
		$this->addTextArea('Text', 'Text');
		
		$this->addSubmit('save');
	}

}
