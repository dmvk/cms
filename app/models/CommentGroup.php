<?php

namespace Model;

use Doctrine\Common\Collections\ArrayCollection;
use Moes\Doctrine\Entities\IdentifiedEntity;

/**
 * @Entity 
 * @Table(name="comments_groups")
 */
class CommentGroup extends IdentifiedEntity
{

	/**
	 * @OneToMany(targetEntity="Comment", mappedBy="group")
	 */
	private $comments;

	public function __construct()
	{
		$this->comments = new ArrayCollection();
	}

	public function getComments()
	{
		return $this->comments;
	}

}