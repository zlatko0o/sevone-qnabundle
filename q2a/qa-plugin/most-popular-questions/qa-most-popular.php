<?php

class qa_most_popular
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
		$themeobject->output( '<h2>Most popular questions</h2>' );

		$data = qa_db_query_sub( "SELECT * FROM `qa_posts` WHERE `type` = 'Q' ORDER BY `views` DESC LIMIT 5" );
		while( $d = $data->fetch_array() )
		{
			$link = qa_q_path( $d['postid'], $d['title'] );
			$themeobject->output( "<a href='{$link}'>{$d['title']}</a><br>" );
		}
	}
}