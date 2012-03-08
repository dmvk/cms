<?php

namespace FrontendModule;

class FrontendJs extends \Moes\WebLoader\JavaScriptLoader
{

	protected function getFiles()
	{
		return array(
			"moes/js/jquery.js",
			"moes/js/jquery.nette.js",
			"moes/js/jquery.ajaxform.js",
			"moes/js/frontend.js",
			"bootstrap/js/bootstrap.js"
		);
	}

}