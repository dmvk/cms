<?php

namespace Moes\Logger;

use Moes\Doctrine\Entities\IdentifiedEntity;
use Moes\Security\Identity;

/**
 * @Entity(repositoryClass="Moes\Logger\Logger")
 * @Table(name="logger") 
 */
class Action extends IdentifiedEntity
{

	/**
	 * @Column
	 */
	private $presenter;

	/**
	 * @Column
	 */
	private $action;

	/**
	 * @Column
	 */
	private $message;

	/**
	 * @ManyToOne(targetEntity="Moes\Security\Identity")
	 */
	private $user;

	/**
	 * @Column(type="datetime")
	 */
	private $timestamp;

	public function __construct()
	{
		$this->timestamp = new \DateTime;
	}

	public function getPresenter()
	{
		return $this->presenter;
	}

	public function setPresenter($presenter)
	{
		$this->presenter = $presenter;
	}

	public function getAction()
	{
		return $this->action;
	}

	public function setAction($action)
	{
		$this->action = $action;
	}

	public function getMessage()
	{
		return $this->message;
	}

	public function setMessage($message)
	{
		$this->message = $message;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function setUser(Identity $user)
	{
		$this->user = $user;
	}
	
	public function getTimestamp()
	{
		return $this->timestamp;
	}

}
