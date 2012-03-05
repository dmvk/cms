<?php

namespace Moes\Security;

use Doctrine\Common\Collections\ArrayCollection,
    Moes\Doctrine\Entities\IEntity,
    Moes\Doctrine\EntityRepository,
    Nette;

/**
 * @Entity
 * @Table(name="users")
 */
class Identity extends Nette\Object implements IEntity, Nette\Security\IIdentity, \Serializable
{

	/**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue
	 */
	private $id;

	/**
	 * @Column
	 */
	private $email;

	/**
	 * @Column(nullable=true)
	 */
	private $password;

	/**
	 * @Column(type="array", nullable=true)
	 */
	private $facebook;

	/**
	 * @ManyToOne(targetEntity="Role")
	 */
	private $role;

	/**
	 * @OneToMany(targetEntity="Model\Article", mappedBy="author")
	 */
	private $articles;

	/**
	 * @OneToMany(targetEntity="Model\Comment", mappedBy="author")
	 */
	private $comments;

	/**
	 * @OneToMany(targetEntity="Model\Page", mappedBy="author")
	 */
	private $pages;

	/**
	 * @var bool
	 */
	private $loaded = TRUE;

	public function __construct()
	{
		$this->articles = new ArrayCollection();
		$this->pages = new ArrayCollection();
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function verifyPassword($password)
	{
		list($salt, $hash, $hashFunction) = explode('$', $this->password);
		return $hash === $hashFunction($salt . $password);
	}

	public function setPassword($password, $hashFunction = "sha1")
	{
		$salt = md5(uniqid("", true));
		$hash = $hashFunction($salt . $password);
		$this->password = $salt . '$' . $hash . '$' . $hashFunction;
	}

	public function getFacebook()
	{
		return $this->facebook;
	}

	public function setFacebook(array $facebook)
	{
		$this->facebook = $facebook;
	}

	public function getArticles()
	{
		return $this->articles;
	}

	public function getPages()
	{
		return $this->pages;
	}
	
	public function getRole()
	{
		return $this->role;
	}
	
	public function setRole($role)
	{
		$this->role = $role;
	}

	// IIdentity interface
	
	public function getId()
	{
		return $this->id;
	}

	public function getRoles()
	{
		return array($this->role->name);
	}

	// Serializable interface

	public function serialize()
	{
		return serialize($this->id);
	}

	public function unserialize($serialized)
	{
		$this->id = unserialize($serialized);
		$this->loaded = FALSE;
 	}

	public function load(EntityRepository $repository)
	{
		return $this->loaded ? $this : $repository->find($this->id);
	}

}