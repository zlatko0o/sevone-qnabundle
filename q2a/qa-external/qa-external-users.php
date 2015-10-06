<?php
/*
	Question2Answer by Gideon Greenspan and contributors
	http://www.question2answer.org/

	File: qa-external-example/qa-external-users.php
	Description: Example of how to integrate with your own user database


	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.question2answer.org/license.php
*/

/* Include the Symfony Kernel */
/*require_once( '/var/www/symfony-projects/device_certification_repository/app/bootstrap.php.cache' );
require_once( '/var/www/symfony-projects/device_certification_repository/app/AppKernel.php' );

$symfonyKernel = new AppKernel( 'dev', true );
$symfonyKernel->boot();
$modifiedSymfonyRequest                = $_SERVER;
$modifiedSymfonyRequest["REQUEST_URI"] = "/";

$tmpSymfonyRequest = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
$symfonyRequest    = $tmpSymfonyRequest->duplicate( null, null, null, null, null, $modifiedSymfonyRequest );
$symfonyResponse   = $symfonyKernel->handle( $symfonyRequest );*/

global $self;
$self = $this;

function Services()
{
	global $self;
	$connect       = new stdClass();
	$connect->user = null;

	$connect->em              = $self->entityManager;
	$connect->router          = $self->router;
	$connect->avatarHelper    = $self->avatarHelper;
	$connect->securityContext = $self->securityContext;
	$connect->securityToken   = $connect->securityContext->getToken();

	if( $connect->securityToken !== null && $connect->securityContext->isGranted( 'IS_AUTHENTICATED_REMEMBERED' ) )
		$connect->user = $connect->securityToken->getUser();

	if( $connect->user == 'anon.' )
		$connect->user = null;

	return $connect;
}

if( !defined( 'QA_VERSION' ) )
{ // don't allow this page to be requested directly from browser
	header( 'Location: ../' );
	exit;
}

function qa_get_mysql_user_column_type()
{
	return 'INT UNSIGNED';
}

function qa_get_login_links( $relative_url_prefix, $redirect_back_to_url )
{
	$router = Services()->router;

	return [
		'login'    => $router->generate( 'aerial_ship_saml_sp.security.login' ),
		'register' => '/app_dev.php/register',
		'logout'   => $router->generate( 'aerial_ship_saml_sp.security.logout' )
	];
}

function qa_get_logged_in_user()
{
	$user = Services()->user;
	if( is_null( $user ) )
		return null;

	$level = QA_USER_LEVEL_BASIC;

	/**
	 * @var \Symfony\Component\Security\Core\SecurityContext $securityContext
	 */
	$securityContext = Services()->securityContext;

	if( $securityContext->isGranted( 'ROLE_DCR_QA_MODERATOR' ) )
		$level = QA_USER_LEVEL_MODERATOR;

	if( $securityContext->isGranted( 'ROLE_DCR_QA_ADMIN' ) )
		$level = QA_USER_LEVEL_ADMIN;

	return [
		'userid'         => $user->getId(),
		'publicusername' => $user->getUsername(),
		'email'          => $user->getEmail(),
		'level'          => $level
	];
}

function qa_get_user_email( $userid )
{
	$user = Services()->em->getRepository( 'SevOneUserBundle:User' )->find( $userid );

	return is_null( $user ) ? null : $user->getEmail();
}

function qa_get_userids_from_public( $publicusernames )
{
	return Services()->em->getRepository( 'SevOneUserBundle:User' )->getUsersByUsernames( $publicusernames );
}

function qa_get_public_from_userids( $userids )
{
	return Services()->em->getRepository( 'SevOneUserBundle:User' )->getUsersByIds( $userids );
}

function qa_get_logged_in_user_html( $logged_in_user, $relative_url_prefix )
{
	return '<a href="/app_dev.php/profile" class="qa-user-link">' . htmlspecialchars( $logged_in_user['publicusername'] ) . '</a>';
}

function qa_get_users_html( $userids, $should_include_link, $relative_url_prefix )
{
	$useridtopublic = qa_get_public_from_userids( $userids );

	$usershtml = [ ];

	foreach( $userids as $userid )
	{
		$publicusername = $useridtopublic[ $userid ];

		$usershtml[ $userid ] = htmlspecialchars( $publicusername );

		if( $should_include_link )
			$usershtml[ $userid ] = '<a href="/app_dev.php/profile/view/' . $userid . '" class="qa-user-link">' . $usershtml[ $userid ] . '</a>';
	}

	return $usershtml;
}

function qa_avatar_html_from_userid( $userid, $size, $padding )
{
	$user      = Services()->em->getRepository( 'SevOneUserBundle:User' )->find( $userid );
	$avatarUrl = Services()->avatarHelper->getAvatarUrlForUser( $user, 32, 32);

	return '<img src="' . $avatarUrl . '" width="32" height="32" class="qa-avatar-image" alt=""/>';
}

function qa_user_report_action( $userid, $action )
{
	// do nothing by default
}