<?php

namespace Model;

use Moes\Doctrine\Entities\IdentifiedEntity;
use Moes\Security\Identity;

/**
 * @Entity(repositoryClass="Model\ArticleRepository")
 * @Table(name="articles")
 * @HasLifecycleCallbacks
 */
class Article extends IdentifiedEntity
{

	CONST DRAFT = "draft";
	CONST PUBLISHED = "published";
	CONST TRASHED = "trashed";

	/**
	 * @Column 
	 */
	private $title;

	/**
	 * @Column(unique=true)
	 */
	private $slug;

	/**
	 * @Column(type="text", nullable=true) 
	 */
	private $introduction;

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
	 * @Column 
	 */
	private $status = self::DRAFT;

	/**
	 * @ManyToOne(targetEntity="Moes\Security\Identity", inversedBy="articles")
	 */
	private $author;

	/**
	 * @ManyToOne(targetEntity="Category", inversedBy="articles")
	 */
	private $category;

	/**
	 * @OneToOne(targetEntity="CommentGroup", cascade={"all"})
	 */
	private $commentGroup;

	public function __construct()
	{
		$this->createdAt = new \DateTime();
		$this->commentGroup = new CommentGroup();
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
		$this->slug = \Nette\Utils\Strings::webalize($slug);
	}

	public function getIntroduction()
	{
		return $this->introduction;
	}

	public function setIntroduction($introduction)
	{
		$this->introduction = $introduction;
	}

	public function getText()
	{
		return $this->text;
	}

	public function setText($text)
	{
		$this->text = $text;
	}

	public function getStatus()
	{
		return $this->status;
	}
	
	public function isPublished()
	{
		return $this->status === self::PUBLISHED;
	}

	/**
	 * Note: use predefined constants 
	 */
	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function getAuthor()
	{
		return $this->author;
	}

	public function setAuthor(Identity $author)
	{
		$this->author = $author;
	}

	public function getCategory()
	{
		return $this->category;
	}

	public function setCategory(Category $category = NULL)
	{
		$this->category = $category;
	}

	public function getCommentGroup()
	{
		return $this->commentGroup;
	}

	// CALLBACKS

	/**
	 * @PrePersist
	 */
	public function prePersist()
	{
		if (empty($this->slug))
			$this->setSlug($this->title);
	}

	/**
	 * @PreUpdate
	 */
	public function preUpdate()
	{
		$this->updatedAt = new \DateTime();
	}

}