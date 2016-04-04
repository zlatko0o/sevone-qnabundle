<?php

global $self;
$self = $this;

if( !defined( 'QA_VERSION' ) )
{ // don't allow this page to be requested directly from browser
	header( 'Location: ../' );
	exit;
}

function qa_questions_last_day( $time )
/*
	Returns all users who have this post as favorite
*/
	{
		$result = qa_db_read_all_assoc(qa_db_query_sub(
			"SELECT postid, userid, title, content, created
			FROM ^posts 
			WHERE type=$ AND created LIKE $",
			'Q', "{$time}%"
		));

		return $result;
	}

function qa_answers_last_day( $time )
/*
	Returns all users who have this post as favorite
*/
	{
		$result = qa_db_read_all_assoc(qa_db_query_sub(
			"SELECT question.postid, answer.userid, question.title, answer.content, answer.created
			FROM ^posts AS answer
			INNER JOIN ^posts AS question ON question.postid = answer.parentid AND question.type=$
			WHERE answer.type=$ AND answer.created LIKE $",
			'Q', 'A', "{$time}%"
		));

		return $result;
	}

function qa_comments_last_day( $time )
/*
	Returns all users who have this post as favorite
*/
	{
		$result = qa_db_read_all_assoc(qa_db_query_sub(
			"SELECT question.postid, comment.userid, question.title, comment.content, comment.created, comment.postid as commentid
			FROM ^posts AS comment
			INNER JOIN ^posts AS answer ON answer.postid = comment.parentid AND answer.type=$
			INNER JOIN ^posts AS question ON question.postid = answer.parentid AND question.type=$
			WHERE comment.type=$ AND comment.created LIKE $",
			'A', 'Q', 'C', "{$time}%"
		));

		return $result;
	}

function qa_questions_no_answer_last_day( $time )
/*
	Returns all users who have this post as favorite
*/
	{
		$result = qa_db_read_all_assoc(qa_db_query_sub(
			"SELECT COUNT(*) AS total
			FROM ^posts AS question
			LEFT OUTER JOIN ^posts AS answer ON answer.parentid = question.postid AND answer.type=$
			WHERE question.type=$ AND question.created LIKE $ AND answer.postid IS NULL",
			'A', 'Q', "{$time}%"
		));

		return $result[0]['total'];
	}