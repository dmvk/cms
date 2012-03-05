<?php

namespace Moes\Logger;

use Moes\Security\Identity;

interface ILogger
{

	const CREATE = "create";
	const READ = "read";
	const UPDATE = "update";
	const DELETE = "delete";
	const OTHER = "other";

	public function logAction($presenter, $action, $message, Identity $user);
}