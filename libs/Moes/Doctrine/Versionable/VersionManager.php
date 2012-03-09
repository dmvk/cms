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
	 * @param Versionable $resource
	 * @return ResourceVersion[]
	 */
	public function getVersions(Versionable $resource)
	{
		$versionableClassName = get_class($resource);
		$versionableClass = $this->em->getClassMetadata($versionableClassName);
		$resourceId = current($versionableClass->getIdentifierValues($resource));

		// INDEX BY bug?
		$query = $this->_em->createQuery(
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

	/**
	 * @param Versionable $resource
	 * @param int $toVersionNum
	 */
	public function revert(Versionable $resource, $toVersionNum)
	{
		$versions = $this->getVersions($resource);
		if (!isset($versions[$toVersionNum])) {
			throw Exception::unknownVersion($toVersionNum);
		}
		/* @var $version Entity\ResourceVersion */
		$version = $versions[$toVersionNum];

		$versionableClass = $this->_em->getClassMetadata(get_class($resource));
		foreach ($version->getVersionedData() AS $k => $v) {
			$versionableClass->reflFields[$k]->setValue($resource, $v);
		}

		if ($versionableClass->changeTrackingPolicy == ClassMetadata::CHANGETRACKING_DEFERRED_EXPLICIT) {
			$this->_em->persist($resource);
		}
	}

}