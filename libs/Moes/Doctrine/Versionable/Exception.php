<?php

namespace Moes\Doctrine\Versionable;

class Exception extends \Exception
{

	static public function versionedEntityRequired()
	{
		return new self("A versioned entity is required if implementing DoctrineExtnsions\Versionable\Versionable interface.");
	}

	static public function singleIdentifierRequired()
	{
		return new self('A single identifier column is required for the Versionable extension.');
	}

	static public function unknownVersion($version)
	{
		return new self('Trying to access an unknown version ' . $version . '.');
	}

}