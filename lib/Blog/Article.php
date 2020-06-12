<?php
namespace Blog;
/**
 * Article
 *
 *
 * @extends Entity
 * @package
 * @subpackage
 * @author     Simon Bergmaier <simon.bergmaier@students.fh-hagenberg.at>
 */
class Article extends Entity {
	/**
	 *
	 * @var integer
	 */
	private $categoryId;
	/**
	 *
	 * @var string
	 */
	private $title;
    /**
     * @var string
     */
    private $subtitle;

    /**
     * @var string
     */
    private $text;
	/**
	 *
	 * @var int
	 */
	private $author;

    /**
     * @var string
     */
    private $creationDate;

    /**
     *
     * @param integer $id
     * @param integer $categoryId
     * @param string $title
     * @param string $subtitle
     * @param string $text
     * @param string $creationDate
     * @param string $author
     */
	public function __construct(int $id, int $categoryId, string $title, string $subtitle, string $text, string $creationDate, string $author)  {
		parent::__construct($id);
		$this->categoryId = intval($categoryId);
		$this->title = $title;
		$this->subtitle = $subtitle;
		$this->text = $text;
		$this->creationDate = $creationDate;
		$this->author = $author;
	}

	/**
	 * getter for the private parameter $categoryId
	 *
	 * @return integer
	 */
	public function getCategoryId() : int {
		return $this->categoryId;
	}

	/**
	 * getter for the private parameter $title
	 *
	 * @return string
	 */
	public function getTitle() : string {
		return $this->title;
	}

    /**
     * @return string
     */
    public function getSubtitle() : string {
	    return $this->subtitle;
    }

    /**
     * @return string
     */
    public function getText(): string {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getCreationDate(): string {
        return $this->creationDate;
    }

	/**
	 * getter for the private parameter $author
	 *
	 * @return string
	 */
	public function getAuthor() :  int {
		return $this->author;
	}

}