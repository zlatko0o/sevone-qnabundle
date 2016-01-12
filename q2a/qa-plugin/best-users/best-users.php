<?php

class BestUsers
{
	public function allow_template( $template )
	{
		$allowed = [
			'activity', 'qa', 'questions', 'hot', 'ask', 'categories', 'question',
			'tag', 'tags', 'unanswered', 'user', 'users', 'search', 'admin', 'custom',
		];

		return in_array( $template, $allowed );
	}

	public function allow_region( $region )
	{
		return ( $region === 'side' );
	}

	public function output_widget( $region, $place, $themeobject, $template, $request, $qa_content )
	{

		$limit = (int)qa_opt( 'best_user_count' );
		if( !is_int( $limit ) || $limit < 0 )
			$limit = 5;

		$themeobject->output( "<h2>Top {$limit} users</h2>" );

		//use usernames as keys (faster)
		$ignoredUsernames = array_flip( explode( ',', qa_opt( 'best_users_ignored_usernames' ) ) );

		if( !is_array( $ignoredUsernames ) )
			$ignoredUsernames = [ ];

		$users = [ ];

		$data = qa_db_query_sub( "SELECT * FROM `^userpoints` ORDER BY `points` DESC LIMIT {$limit}" );
		while( $d = $data->fetch_array() )
		{
			$users[ $d['userid'] ] = $d['points'];
		}

		$userIds   = array_keys( $users );
		$userInfo  = qa_get_public_from_userids( $userIds );
		$userLinks = qa_get_users_html( $userIds, true, true );;

		if( !empty( $userIds ) )
		{
			$themeobject->output( "<ol>" );
			foreach( $users as $userId => $points )
			{
				$link     = $userLinks[ $userId ];
				$username = $userInfo[ $userId ];

				//skip ignored users
				if( !isset( $ignoredUsernames[ $username ] ) )
					$themeobject->output( "<li>$link ({$points})</li>" );

			}
			$themeobject->output( "</ol>" );
		}

	}

	public function admin_form( &$qa_content )
	{

		//	Process form input

		$saved = false;

		if( qa_clicked( 'best_users_save' ) )
		{
			qa_opt( 'best_user_count', (int)qa_post_text( 'best_user_count' ) );
			qa_opt( 'best_users_ignored_usernames', qa_post_text( 'best_users_ignored_usernames' ) );
			$saved = true;
		}

		return [
			'ok' => ( $saved && !isset( $error ) ) ? 'Best users settings saved' : null,

			'fields' => [
				[
					'label' => 'Users count',
					'tags'  => 'name="best_user_count"',
					'value' => qa_opt( 'best_user_count' ),
					'type'  => 'input',
				],
				[
					'label' => 'Ignored users (comma separated list with usernames)',
					'tags'  => 'name="best_users_ignored_usernames"',
					'value' => qa_opt( 'best_users_ignored_usernames' ),
					'type'  => 'input',
				],
			],

			'buttons' => [
				[
					'label' => 'Save Changes',
					'tags'  => 'name="best_users_save"',
				],
			],
		];
	}
}