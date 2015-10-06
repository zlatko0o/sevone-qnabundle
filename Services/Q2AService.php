<?php

namespace SevOne\QnABundle\Services;

class Q2AService
{
	/**
	 * @var QnAExternalUser
	 */
	public $QnAExternal;

	public function __construct( $qnAExternal )
	{
		$this->QnAExternal = $qnAExternal;
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

		$config = $this->QnAExternal->getConfig();
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