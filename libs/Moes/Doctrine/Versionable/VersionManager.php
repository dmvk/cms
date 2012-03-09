<?php

namespace Moes\Doctrine\Versionable;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

class VersionManager
{

	private $em;

	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}

	/**
	 * Return all versions of an versionable entity.
	 * 
	 * @param IVersionable $resource
	 * @return ResourceVersion[]
	 */
	public function getVersions(IVersionable $resource)
	{
		$versionableClassName = get_class($resource);
		$versionableClass = $this->em->getClassMetadata($versionableClassName);
		$resourceId = current($versionableClass->getIdentifierValues($resource));

		// INDEX BY bug?
		$query = $this->em->createQuery(
			"SELECT v FROM Moes\Doctrine\Versionable\ResourceVersion v INDEX BY v.version " .
			"WHERE v.resourceName = ?1 AND v.resourceId = ?2 ORDER BY v.version DESC");
		$query->setParameter(1, $versionableClassName);
		$query->setParameter(2, $resourceId);

		$newVersions = array();
		foreach ($query->getResult() AS $version) {
			$newVersions[$version->getVersion()] = $version;
		}
		return $newVersions;
	}

	public function getVersion(IVersionable $resource, $version)
	{
		$versions = $this->getVersions($resource);

		if (!isset($versions[$version])) {
			throw Exception::unknownVersion($version);
		}

		return $versions[$version];
	}

	/**
	 * @param IVersionable $resource
	 * @param int $toVersionNum
	 */
	public function revert(IVersionable $resource, $toVersionNum)
	{
		$versions = $this->getVersions($resource);
		if (!isset($versions[$toVersionNum])) {
			throw Exception::unknownVersion($toVersionNum);
		}
		/* @var $version Entity\ResourceVersion */
		$version = $versions[$toVersionNum];

		$versionableClass = $this->em->getClassMetadata(get_class($resource));
		foreach ($version->getVersionedData() AS $k => $v) {
			$versionableClass->reflFields[$k]->setValue($resource, $v);
		}

		if ($versionableClass->changeTrackingPolicy == ClassMetadata::CHANGETRACKING_DEFERRED_EXPLICIT) {
			$this->em->persist($resource);
		}
	}

}