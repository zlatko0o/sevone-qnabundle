<?php

namespace SevOne\DcrBundle\Services;

use Doctrine\ORM\EntityManager;
use SevOne\userBundle\Services\AvatarHelper;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\SecurityContextInterface;

class Q2AService
{
	/**
	 * @var \Symfony\Component\HttpFoundation\Request
	 */
	public $request;

	/**
	 * @var EntityManager
	 */
	public $entityManager;

	/**
	 * @var SecurityContextInterface
	 */
	public $securityContext;

	/**
	 * @var Router
	 */
	public $router;

	/**
	 * @var AvatarHelper
	 */
	public $avatarHelper;


	public function __construct( RequestStack $requestStack, EntityManager $entityManager, SecurityContextInterface $securityContext, Router $router, AvatarHelper $avatarHelper )
	{
		$this->request = $requestStack->getCurrentRequest();
		$this->entityManager = $entityManager;
		$this->securityContext = $securityContext;
		$this->router = $router;
		$this->avatarHelper = $avatarHelper;
	}

	public function getPath()
	{
		$webFolder = $this->request->server->get( 'DOCUMENT_ROOT' ) . DIRECTORY_SEPARATOR;
		$q2aFolder = 'q2a' . DIRECTORY_SEPARATOR;

		return $webFolder . $q2aFolder;
	}

	public function defineConstants( $path )
	{
		define( 'QA_BASE_DIR', $path );
		define( 'IN_SEVONE', true );
	}

	public function getResponse( $path )
	{
		$q2aPath = $this->getPath();
		$this->defineConstants( $q2aPath );

		$path               = str_replace( 'qna/', '', $path );
		$_GET['qa-rewrite'] = $path;

		ob_start();
		require $q2aPath . 'qa-include/qa-index.php';
		$response = ob_get_clean();

		return $response;
	}

	public function getSearchResults( $criteria, $start, $maxResults,  $loggedUser, $fullcontent = false )
	{
		$path = $this->getPath();
		$this->defineConstants( $path );

		require_once $path . 'qa-include/qa-base.php';
		require_once $path . 'qa-external/qa-external-users.php';
		require_once $path . 'qa-include/app/search.php';

		return qa_get_search_results( $criteria, $start, $maxResults, $loggedUser->getId(), false, $fullcontent );
	}
}