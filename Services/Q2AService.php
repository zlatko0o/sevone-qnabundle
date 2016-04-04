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
	
		public function digestEmail( $time )
	{
		$path = $this->getPath();
		$this->defineConstants( $path );

		require_once $path . 'qa-include/qa-base.php';
		require_once $path . 'qa-external/qa-forum-reports.php';

		$questions = array_map( function ( $item ) {
			$item['action'] = 'wrote a question -';
			$item['url'] = $item['postid'];
			return $item;
		}, qa_questions_last_day( $time ) );

		$answers = array_map( function ( $item ) {
			$item['action'] = 'answered on';
			$item['url'] = $item['postid'];
			return $item;
		}, qa_answers_last_day( $time ) );

		$comments = array_map( function ( $item ) {
			$item['action'] = 'commented on';
			$item['url'] = $item['postid'] . '?show='.$item['commentid'].'#c' . $item['commentid'];
			return $item;
		}, qa_comments_last_day( $time ) );

		$count = [
			'questions' => count( $questions ),
			'questions_no_answers' => qa_questions_no_answer_last_day( $time ),
			'answers'   => count( $answers ),
			'comments'  => count( $comments )
		];

		$activity = array_merge( $questions, $answers, $comments );

		usort( $activity, function ( $first, $second )
		{
			return $first['created'] < $second['created'];
		} );

		$QnAExternal = $this->QnAExternal;
		$foundUsers = $QnAExternal->getContainer()
								  ->get( 'doctrine' )->getEntityManager()
								  ->createQueryBuilder()->select( 'u.id, u.username' )->from( 'SevOneUserBundle:User', 'u' )
								  ->getQuery()
								  ->getResult( \Doctrine\ORM\Query::HYDRATE_ARRAY );

		$users = [ ];
		foreach ($foundUsers as $user)
		{
			$users[$user['id']] = $user;
		}

		$activity = array_map( function ( $item ) use ( $QnAExternal, $users ) {
			if (array_key_exists($item['userid'], $users)) {
				$item['user'] = $users[ $item['userid'] ];
			} else {
				$item['user'] = [
					'id' => '',
					'username' => ''
				];
			}

			return $item;
		}, $activity );

		$data = [
			'count' => $count,
			'activity' => $activity
		];

		$QnAExternal->getContainer()->get( 'connect.email_notifications' )->sendForumDigestActivityToAdmins( $data );
	}
}