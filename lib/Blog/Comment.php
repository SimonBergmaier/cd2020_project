<?php
namespace Blog;
/**
 * Comment
 *
 *
 * @extends Entity
 * @package
 * @subpackage
 * @author     Simon Bergmaier <simon.bergmaier@students.fh-hagenberg.at>
 */
class Comment extends Entity {
	/**
	 *
	 * @var integer
	 */
	private $articleId;
	/**
	 *
	 * @var integer
	 */
	private $authorId;
    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $creationDate;


    /**
     *
     * @param integer $id
     * @param int $articleId
     * @param int $authorId
     * @param string $text
     * @param string $creationDate
     */
	public function __construct(int $id, int $articleId, int $authorId, string $text, string $creationDate)  {
		parent::__construct($id);
		$this->articleId = intval($articleId);
		$this->authorId = intval($authorId);
		$this->text = $text;
		$this->creationDate = $creationDate;
	}

	/**
	 * getter for the private parameter $authorId
	 *
	 * @return integer
	 */
	public function getAuthor() : int {
		return $this->authorId;
	}

	/**
	 * getter for the private parameter $articleId
	 *
	 * @return integer
	 */
	public function getArticleId() : int {
		return $this->articleId;
	}

	/**
	 * getter for the private parameter $text
	 *
	 * @return string
	 */
	public function getText() :  string {
		return $this->text;
	}

    /**
     * @return string
     */
    public function getCreationDate() : string {
	    return $this->creationDate;
    }

}