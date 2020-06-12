<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 23/03/2018
 * Time: 10:28
 */

namespace Blog;

interface IData {
	public function getId() : int;
}

class Entity extends BaseObject implements IData {
	private $id;

	public function __construct(int $id) {
		$this->id = $id;
	}

	public function getId() : int {
		return $this->id;
	}
}