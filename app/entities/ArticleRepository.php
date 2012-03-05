<?php

namespace Model;

use Moes\Doctrine\EntityRepository;

class ArticleRepository extends EntityRepository
{

	public function draft(Article $entity)
	{
		$entity->setStatus(Article::DRAFT);
		$this->save($entity);
	}

	public function publish(Article $entity)
	{
		$entity->setStatus(Article::PUBLISHED);
		$this->save($entity);
	}

	public function trash(Article $entity)
	{
		$entity->setStatus(Article::TRASHED);
		$this->save($entity);
	}

	public function findPublished()
	{
		$article = $this->getEntityName();

		return $this->createQuery("SELECT a FROM $article a WHERE a.status = :status ORDER BY a.createdAt")
			->setParameter("status", Article::PUBLISHED)
			->getResult();
	}

}
