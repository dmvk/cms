<?php

namespace FrontendModule;

class FrontendJs extends \Moes\WebLoader\JavaScriptLoader
{

	protected function getFiles()
	{
		return array(
			"js/jquery.js",
			"js/nette.js"
		);
	}

}