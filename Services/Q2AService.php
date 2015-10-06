<?php

namespace SevOne\QnABundle\Services;

use Doctrine\ORM\EntityManager;
use SevOne\userBundle\Services\AvatarHelper;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;
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
	/**
	 * @var ContainerInterface
	 */
	private $container;

	public function __construct(
		RequestStack $requestStack,
		EntityManager $entityManager,
		SecurityContextInterface $securityContext,
		Router $router,
		AvatarHelper $avatarHelper,
		ContainerInterface $container )
	{
		$this->request         = $requestStack->getCurrentRequest();
		$this->entityManager   = $entityManager;
		$this->securityContext = $securityContext;
		$this->router          = $router;
		$this->avatarHelper    = $avatarHelper;
		$this->container       = $container;
	}

	public function getPath()
	{
		$webFolder = realpath( __DIR__ . '/..' ) . DIRECTORY_SEPARATOR;
		$q2aFolder = 'q2a' . DIRECTORY_SEPARATOR;

		return $webFolder . $q2aFolder;
	}

	public function defineConstants( $path )
	{
		define( 'QA_BASE_DIR', $path );
		define( 'IN_SEVONE', true );
		
		$config = $this->getConfig();
		define( 'QA_MYSQL_HOSTNAME', $config['database_host'] );
		define( 'QA_MYSQL_USERNAME', $config['database_user'] );
		define( 'QA_MYSQL_PASSWORD', $config['database_password'] );
		define( 'QA_MYSQL_DATABASE', $config['q2a_database_name'] );
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

	private function getConfig()
	{
		return [
			'database_host'     => $this->container->getParameter( 'database_host' ),
			'database_user'     => $this->container->getParameter( 'database_user' ),
			'database_password' => $this->container->getParameter( 'database_password' ),
			'q2a_database_name' => $this->container->getParameter( 'q2a_database_name' ),
		];
	}

	public function getSearchResults( $criteria, $start, $maxResults, $loggedUser, $fullcontent = false )
	{
		$path = $this->getPath();
		$this->defineConstants( $path );

		require_once $path . 'qa-include/qa-base.php';
		require_once $path . 'qa-external/qa-external-users.php';
		require_once $path . 'qa-include/app/search.php';

		return qa_get_search_results( $criteria, $start, $maxResults, $loggedUser->getId(), false, $fullcontent );
	}
}