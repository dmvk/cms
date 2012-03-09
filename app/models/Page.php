<?php

namespace Model;

use Moes\Doctrine\Entities\IdentifiedEntity;
use Moes\Doctrine\Versionable\IVersionable;
use Moes\Security\Identity;

/**
 * @Entity
 * @Table(name="pages")
 * @HasLifecycleCallbacks
 */
class Page extends IdentifiedEntity implements IVersionable
{

	/**
	 * @Column
	 */
	private $title;

	/**
	 * @Column(unique=true)
	 */
	private $slug;
 
	/**
	 * @Column(type="text")
	 */
	private $text;

	/**
	 * @Column(type="datetime")
	 */
	private $createdAt;

	/**
	 * @Column(type="datetime", nullable=true)
	 */
	private $updatedAt;

	/**
	 * @Version
	 * @Column(type="integer") 
	 */
	private $version;

	/**
	 * @ManyToOne(targetEntity="Moes\Security\Identity", inversedBy="pages") 
	 */
	private $author;

	public function __construct()
	{
		$this->createdAt = new \DateTime;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getSlug()
	{
		return $this->slug;
	}

	public function setSlug($slug)
	{
		$this->slug = \Nette\Utils\Strings::webalize($slug, '/');
	}

	public function getText()
	{
		return $this->text;
	}

	public function setText($text)
	{
		$this->text = $text;
	}

	public function getCreatedAt()
	{
		return $this->createdAt;
	}

	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}

	public function getAuthor()
	{
		return $this->author;
	}

	public function setAuthor(Identity $author)
	{
		$this->author = $author;
	}

	// CALLBACKS

	/**
	 * @PrePersist
	 */
	public function prePersist()
	{
		if ($this->slug === NULL)
			$this->setSlug($this->title);
	}

	/**
	 * @PreUpdate
	 */
	public function preUpdate()
	{
		$this->updatedAt = new \DateTime;
	}

}