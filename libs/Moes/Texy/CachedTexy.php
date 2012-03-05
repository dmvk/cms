<?php

namespace Moes\Texy;

use Nette\Caching;

class CachedTexy extends \Texy
{

	/**
	 * @var Caching\Cache
	 */
	private $cache;

	public function __construct(Caching\IStorage $storage)
	{
		parent::__construct();

		$this->addHandler('block', new FSHLHandler());
		$this->cache = new Caching\Cache($storage, 'Moes.Texy');
	}

	public function process($text, $singleLine = FALSE)
	{
		$key = substr(md5($text), 0, 12);
		return $this->cache->load($key) ?: $this->cache->save($key, parent::process($text), array(Caching\Cache::EXPIRATION => '+ 60 minutes'));
	}

}