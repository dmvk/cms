<?php

namespace BackendModule;

class BackendJs extends \Moes\WebLoader\JavaScriptLoader
{

	protected function getFiles()
	{
		return array(
			"moes/js/netteForms.js",
			"moes/js/jquery.js",
			"moes/js/jquery.nette.js",
			"markitup/jquery.markitup.js",
			"markitup/sets/texy/set.js",
			"bootstrap/js/bootstrap.js"
		);
	}

}