<?php

namespace FrontendModule;

class FrontendCss extends \Moes\WebLoader\CssLoader
{

	protected function getFiles()
	{
		return array(
			"bootstrap/css/bootstrap.css",
			"moes/css/frontend.less",
			"moes/css/fshl.css"
		);
	}

}