<?php

namespace BackendModule;

class BackendCss extends \Moes\WebLoader\CssLoader
{

	protected function getFiles()
	{
		return array(
			"bootstrap/css/bootstrap.css",
			"moes/css/backend.less",
			"markitup/skins/simple/style.css",
			"markitup/sets/texy/style.css"
		);
	}

}