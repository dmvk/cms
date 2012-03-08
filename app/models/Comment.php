<?php

namespace Model;

use Moes\Doctrine\Entities\IdentifiedEntity;

/**
 * @Entity(repositoryClass="Model\CommentRepository")
 * @Table(name="comments")
 */
class Comment extends IdentifiedEntity
{

	/**
	 * @Column(type="text")
	 */
	private $text;

	/**
	 * @Column(type="integer")
	 */
	private $rating = 0;

	/**
	 * @Column(type="boolean")
	 */
	private $approved = TRUE;

	/**
	 * @ManyToOne(targetEntity="CommentGroup", inversedBy="comments")
	 */
	private $group;

	/**
	 * @ManyToOne(targetEntity="Moes\Security\Identity", inversedBy="comments")
	 */
	private $author;

	public function getText()
	{
		return $this->text;
	}

	public function setText($text)
	{
		$this->text = $text;
	}

	public function getRating()
	{
		return $this->rating;
	}

	public function setRating($rating)
	{
		$this->rating = $rating;
	}

	public function isApproved()
	{
		return $this->approved;
	}
	
	public function setApproved($approved)
	{
		$this->approved = $approved;
	}

	public function getGroup()
	{
		return $this->group;
	}

	public function setGroup(CommentGroup $group)
	{
		$this->group = $group;
	}

	public function getAuthor()
	{
		return $this->author;
	}

	public function setAuthor($author)
	{
		$this->author = $author;
	}

}