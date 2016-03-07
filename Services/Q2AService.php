<?php

namespace SevOne\QnABundle\Services;

class Q2AService
{
	/**
	 * @var QnAExternal
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
		if( !defined( 'QA_BASE_DIR' ) )
			define( 'QA_BASE_DIR', $path );
		if( !defined( 'IN_SEVONE' ) )
			define( 'IN_SEVONE', true );

		$config = $this->QnAExternal->getConfig();
		if( !defined( 'QA_MYSQL_HOSTNAME' ) )
			define( 'QA_MYSQL_HOSTNAME', $config['database_host'] );
		if( !defined( 'QA_MYSQL_USERNAME' ) )
			define( 'QA_MYSQL_USERNAME', $config['database_user'] );
		if( !defined( 'QA_MYSQL_PASSWORD' ) )
			define( 'QA_MYSQL_PASSWORD', $config['database_password'] );
		if( !defined( 'QA_MYSQL_DATABASE' ) )
			define( 'QA_MYSQL_DATABASE', $config['q2a_database_name'] );
	}

	public function getResponse( $path )
	{
		$q2aPath = $this->getPath();
		$this->defineConstants( $q2aPath );

		$path               = str_replace( 'forums/', '', $path );
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

		return qa_get_search_results( $criteria, $start, $maxResults, is_null( $loggedUser ) ? null : $loggedUser->getId(), false, $fullcontent, true );
	}

	public function processPostImages( $message )
	{
		$service = $this->QnAExternal->getCkeFileBrowser();

		return $service->processImages( $message, $this->getContainer()->get( 'security.context' )->getToken()->getUser() );
	}

	public function getContainer()
	{
		return $this->QnAExternal->getContainer();
	}

	public function emitAnswerAddedEvent( $params, $userid, $uids )
	{
		$this->QnAExternal->emitAnswerAddedEvent( $params, $userid, $uids );
	}

	public function emitCommentAddedEvent( $params, $user, $uids )
	{
		$this->QnAExternal->emitCommentAddedEvent( $params, $user, $uids );
	}

	public function emitBestAnswerSelectedEvent( $params, $user, $recipient )
	{
		$this->QnAExternal->emitBestAnswerSelectedEvent( $params, $user, $recipient );
	}
}