<?php
namespace Blog;
use Data\DataManager;

/**
 * Controller
 *
 * class handles POST requests and redirects
 * the client after processing
 * - demo of singleton pattern
 */
class Controller extends BaseObject {
	// static strings used in views

	const ACTION = 'action';
	const PAGE = 'page';
	const ACTION_ADD = 'addArticle';
	const ACTION_REMOVE = 'removeArticle';
	const ACTION_ACOMMENT = 'addComment';
	const ACTION_RCOMMENT = 'removeComment';
	const ACTION_ECOMMENT = 'editComment';
	const ACTION_LOGIN = 'login';
	const ACTION_LOGOUT = 'logout';
	const ACTION_REGISTER = 'register';
	const USER_NAME = 'userName';
	const USER_PASSWORD = 'password';
	const USER_PASSWORDREP = 'passwordRep';
	const TEXT = 'text';
	const SUBTITLE = 'subTitle';
	const TITLE = 'title';
	const CATEGORY = 'category';
	const ARTICLE_ID = 'articleId';
	const ACTION_EDIT = 'editArticle';
	const COMMENT_ID = 'commentId';

	private static $instance = false;

	/**
	 *
	 * @return Controller
	 */
	public static function getInstance() : Controller {

		if (!self::$instance) {
			self::$instance = new Controller();
		}
		return self::$instance;
	}

	private function __construct() {

	}

	/**
	 *
	 * processes POST requests and redirects client depending on selected
	 * action
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function invokePostAction() : bool {

		if ($_SERVER['REQUEST_METHOD'] != 'POST') {
			throw new \Exception('Controller can only handle POST requests.');
			return null;
		}
		elseif (!isset($_REQUEST[self::ACTION])) {
			throw new \Exception(self::ACTION . ' not specified.');
			return null;
		}


		// now process the assigned action
		$action = $_REQUEST[self::ACTION];

		switch ($action) {

			case self::ACTION_ADD :
                $user = AuthenticationManager::getAuthenticatedUser();
                if($user==null) {
                    $this->forwardRequest(['Not logged in.']);
                    break;
                }

                if(!$this->addArticle($user->getId(), $_REQUEST[self::CATEGORY], $_REQUEST[self::TITLE], $_REQUEST[self::SUBTITLE], $_REQUEST[self::TEXT])) {
                    $this->forwardRequest(['Article could not be created!']);
                }
				break;

			case self::ACTION_REMOVE :
                DataManager::removeArticle($_REQUEST[self::ARTICLE_ID]);
                Util::redirect('index.php?view=list');
				break;

			case self::ACTION_LOGIN :
                if(!AuthenticationManager::authenticate($_REQUEST[self::USER_NAME],$_REQUEST[self::USER_PASSWORD])) {
                    $this->forwardRequest(['Not logged in.']);
                }
                Util::redirect();
				break;

            case self::ACTION_EDIT :

                $user = AuthenticationManager::getAuthenticatedUser();
                if($user==null) {
                    $this->forwardRequest(['Not logged in.']);
                    break;
                }

                if(!$this->editArticle($_REQUEST[self::ARTICLE_ID], $_REQUEST[self::TITLE], $_REQUEST[self::SUBTITLE], $_REQUEST[self::TEXT], $_REQUEST[self::CATEGORY])) {
                    $this->forwardRequest(['Article could not be edited!']);
                }
                break;

            case self::ACTION_ACOMMENT :
                $user = AuthenticationManager::getAuthenticatedUser();
                if($user==null)  {
                    $this->forwardRequest(['Not logged in.']);
                    break;
                }

                if(!$this->addComment($user->getId(), $_REQUEST[self::ARTICLE_ID], $_REQUEST[self::TEXT])) {
                    $this->forwardRequest(['Comment could not be created!']);
                }
                break;

            case self::ACTION_ECOMMENT :
                $user = AuthenticationManager::getAuthenticatedUser();
                if($user==null) {
                    $this->forwardRequest(['Not logged in.']);
                    break;
                }

                if(!$this->editComment($_REQUEST[self::COMMENT_ID], $_REQUEST[self::ARTICLE_ID], $_REQUEST[self::TEXT])) {
                    $this->forwardRequest(['Comment could not be edited!']);
                }
                break;

            case self::ACTION_RCOMMENT :
                DataManager::removeComment($_REQUEST[self::COMMENT_ID]);
                Util::redirect('index.php?view=article&id='.$_REQUEST[self::ARTICLE_ID]);
                break;

            case self::ACTION_REGISTER :
                if($_REQUEST[self::USER_PASSWORD] === $_REQUEST[self::USER_PASSWORDREP]) {
                    $user = Util::escape($_REQUEST[self::USER_NAME]);
                    DataManager::registerUser($user, $_REQUEST[self::USER_PASSWORD]);
                    AuthenticationManager::authenticate($user, $_REQUEST[self::USER_PASSWORD]);
                    Util::redirect('index.php');
                } else {
                    $this->forwardRequest(["Passwords didn't match"]);
                }

                break;

			case self::ACTION_LOGOUT :
                AuthenticationManager::signOut();
                Util::redirect();
				break;

			default :
				throw new \Exception('Unknown controller action: ' . $action);
				break;
		}
	}

    /**
     *
     * @param array $errors : optional assign it to
     * @param string $target : url for redirect of the request
     */
    protected function forwardRequest(array $errors = null, $target = null) {
        //check for given target and try to fall back to previous page if needed
        if ($target == null) {
            if (!isset($_REQUEST[self::PAGE])) {
                throw new Exception('Missing target for forward.');
            }
            $target = $_REQUEST[self::PAGE];
        }
        //forward request to target
        // optional - add errors to redirect and process them in view
        if (count($errors) > 0)
            $target .= '&errors=' . urlencode(serialize($errors));
        header('location: ' . $target);
        exit();
    }

    private function removeScripts(string $text) : string {
        return preg_replace("/<script.*?<\/script>/s", "", $text);
    }

    protected function addArticle(int $user, int $categoryId, string $title, string $subtitle, string $text) : bool {
        $errors = [];
        $title = trim($title);
        $subtitle = trim($subtitle);

        $title = Util::escape($title);
        $subtitle = Util::escape($subtitle);
        $text = $this->removeScripts($text);

        if($title == null || strlen($title) == 0) {
            $errors[] = 'No title was given.';
        }

        if($subtitle == null || strlen($subtitle) == 0) {
            $errors[] = 'No subtitle was given.';
        }

        if(sizeof($errors) > 0) {
            $this->forwardRequest($errors);
            return false;
        }

        $articleId = DataManager::addArticle($user, $categoryId, $title, $subtitle, $text);

        if(!isset($articleId)) {
            $this->forwardRequest(['Could not create Article']);
            return false;
        }

        Util::redirect('index.php?view=article&id=' . rawurlencode($articleId));
        return true;
    }

    protected function editArticle(int $articleId, string $title, string $subtitle, string $text, int $categoryId) : bool {
        $errors = [];
        $title = trim($title);
        $subtitle = trim($subtitle);

        $title = Util::escape($title);
        $subtitle = Util::escape($subtitle);
        $text = $this->removeScripts($text);

        if($title == null || strlen($title) == 0) {
            $errors[] = 'No title was given.';
        }

        if($subtitle == null || strlen($subtitle) == 0) {
            $errors[] = 'No subtitle was given.';
        }

        if(sizeof($errors) > 0) {
            $this->forwardRequest($errors);
            return false;
        }

        $ret = DataManager::editArticle($articleId, $title, $subtitle, $text, $categoryId);

        if(!$ret) {
            $this->forwardRequest(['Could not edit Article']);
            return false;
        }

        Util::redirect('index.php?view=article&id=' . rawurlencode($articleId));
        return true;
    }

    private function addComment(int $user, int $articleId, string $text) : bool {
        $errors = [];

        $text = $this->removeScripts($text);

        if($text == null || strlen($text) == 0) {
            $errors[] = 'No Comment was given.';
        }

        if(sizeof($errors) > 0) {
            $this->forwardRequest($errors);
            return false;
        }

        $ret = DataManager::addComment($user, $articleId, $text);

        if(!$ret) {
            $this->forwardRequest(['Could not create Comment']);
            return false;
        }

        Util::redirect('index.php?view=article&id=' . rawurlencode($articleId));
        return true;
    }

    private function editComment(int $commentId,int $articleId, string $text) : bool {
        $errors = [];

        $text = $this->removeScripts($text);

        if($text == null || strlen($text) == 0) {
            $errors[] = 'No text was given.';
        }

        if(sizeof($errors) > 0) {
            $this->forwardRequest($errors);
            return false;
        }

        $ret = DataManager::editComment($commentId, $text);

        if(!$ret) {
            $this->forwardRequest(['Could not edit Comment']);
            return false;
        }

        Util::redirect('index.php?view=article&id=' . rawurlencode($articleId));
        return true;
    }
}