<?php

namespace Model;

use Moes\Doctrine\Entities\IdentifiedEntity;

/**
 * @Entity
 * @Table(name="comments")
 */
class Comment extends IdentifiedEntity
{

	/**
	 * @Column(type="text")
	 */
	private $text;

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

	public function getGroup()
	{
		return $this->group;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function setUser($user)
	{
		$this->user = $user;
	}

}