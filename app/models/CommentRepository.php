<?php

namespace Model;

use Moes\Doctrine\EntityRepository;

class CommentRepository extends EntityRepository
{

	public function like(Comment $comment)
	{
		$comment->rating++;
		$this->save($comment);
	}

	public function dislike(Comment $comment)
	{
		$comment->rating--;
		$this->save($comment);
	}

	public function approve(Comment $comment)
	{
		$comment->approved = TRUE;
		$this->save($comment);
	}

	public function disapprove(Comment $comment)
	{
		$comment->approved = FALSE;
		$this->save($comment);
	}

}