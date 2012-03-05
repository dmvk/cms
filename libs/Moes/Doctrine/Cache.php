<?php

namespace Moes\Doctrine;

use Nette;
use Doctrine;

class Cache extends Doctrine\Common\Cache\CacheProvider
{

	/** @var Nette\Caching\Cache */
	private $cache;

	/**
	 * @param Nette\Caching\IStorage
	 */
	public function __construct(Nette\Caching\IStorage $storage)
	{
		$this->cache = new Nette\Caching\Cache($storage, "Moes.Doctrine");
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleteAll()
	{
		$this->cache->clean(array('tags' => array('doctrine')));
		return TRUE;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function doFetch($id)
	{
		return $this->cache->load($id) ? : FALSE;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function doContains($id)
	{
		return $this->cache->load($id) !== NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function doSave($id, $data, $lifeTime = 0)
	{
		$files = array();
		if ($data instanceof Doctrine\ORM\Mapping\ClassMetadata) {
			$files[] = Nette\Reflection\ClassType::from($data->name)->getFileName();
			foreach ($data->parentClasses as $class) {
				$files[] = Nette\Reflection\ClassType::from($class)->getFileName();
			}
		}

		$dp = array('tags' => array('doctrine'), 'files' => $files);

		if ($lifeTime != 0) {
			$dp['expire'] = time() + $lifeTime;
		}

		$this->cache->save($id, $data, $dp);

		return TRUE;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function doDelete($id)
	{
		$this->cache->save($id, NULL);
		return TRUE;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function doFlush()
	{
		$this->deleteAll();
	}

	/**
	 * {@inheritdoc}
	 */
	protected function doGetStats()
	{
		return NULL;
	}

}
