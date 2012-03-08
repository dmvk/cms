<?php

namespace Moes\Security;

use Nette\Config\Loader;
use Nette\Security\Permission;

class Authorizator extends Permission
{

	public function __construct()
	{
		$this->addRoles();
		$this->addResources();

		// host
		$this->allow("guest", array("article", "comment", "page"), "view");

		// registrovany
		$this->allow("registered", "comment", array("add", "vote"));		
		
		// administrator
		$this->allow("administrator", "comment", "edit");
	}

	private function addRoles()
	{
		$this->addRole("guest");
		$this->addRole("registered", "guest");
		$this->addRole("moderator", "registered");
		$this->addRole("administrator", "moderator");
	}

	private function addResources()
	{
		$this->addResource("article");
		$this->addResource("comment");
		$this->addResource("page");
	}

}