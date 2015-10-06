<?php

global $self;
$self = $this;

if( !defined( 'QA_VERSION' ) )
{ // don't allow this page to be requested directly from browser
	header( 'Location: ../' );
	exit;
}

function qa_get_mysql_user_column_type()
{
	global $self;

	return $self->qnAExternal->qa_get_mysql_user_column_type();
}

function qa_get_login_links( $relative_url_prefix, $redirect_back_to_url )
{
	global $self;

	return $self->qnAExternal->qa_get_login_links( $relative_url_prefix, $redirect_back_to_url );
}

function qa_get_logged_in_user()
{
	global $self;

	return $self->qnAExternal->qa_get_logged_in_user();
}

function qa_get_user_email( $userid )
{
	global $self;

	return $self->qnAExternal->qa_get_user_email( $userid );
}

function qa_get_userids_from_public( $publicusernames )
{
	global $self;

	return $self->qnAExternal->qa_get_userids_from_public( $publicusernames );
}

function qa_get_public_from_userids( $userids )
{
	global $self;

	return $self->qnAExternal->qa_get_public_from_userids( $userids );
}

function qa_get_logged_in_user_html( $logged_in_user, $relative_url_prefix )
{
	global $self;

	return $self->qnAExternal->qa_get_logged_in_user_html( $logged_in_user, $relative_url_prefix );

}

function qa_get_users_html( $userids, $should_include_link, $relative_url_prefix )
{
	global $self;

	return $self->qnAExternal->qa_get_users_html( $userids, $should_include_link, $relative_url_prefix );

}

function qa_avatar_html_from_userid( $userid, $size, $padding )
{
	global $self;

	return $self->qnAExternal->qa_avatar_html_from_userid( $userid, $size, $padding );
}

function qa_user_report_action( $userid, $action )
{
	global $self;

	return $self->qnAExternal->qa_user_report_action( $userid, $action );
}