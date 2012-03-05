<?php

namespace BackendModule;

class BackendJs extends \Moes\WebLoader\JavaScriptLoader
{

	protected function getFiles()
	{
		return array(
			"js/netteForms.js",
			"js/jquery.js",
			"js/nette.js",
			"markitup/jquery.markitup.js",
			"markitup/sets/texy/set.js",
			"bootstrap/js/bootstrap.js"
		);
	}

}