<?php

namespace Model;

use Doctrine\Common\Collections\ArrayCollection;
use Moes\Doctrine\Entities\IdentifiedEntity;

/**
 * @Entity 
 */
class Category extends IdentifiedEntity
{

	/**
	 * @Column 
	 */
	private $name;

	/**
	 * @Column(type="text", nullable=true) 
	 */
	private $description;

	/**
	 * @OneToMany(targetEntity="Article", mappedBy="category")
	 */
	private $articles;

	public function __construct()
	{
		$this->articles = new ArrayCollection();
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getDecription()
	{
		return $this->description;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	/**
	 * @return ArrayCollection
	 */
	public function getArticles()
	{
		return $this->articles;
	}

}