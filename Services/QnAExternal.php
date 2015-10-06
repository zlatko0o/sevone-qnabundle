<?php

namespace SevOne\QnABundle\Services;

use Symfony\Component\Intl\Exception\NotImplementedException;

abstract class QnAExternal
{
	public function qa_get_mysql_user_column_type()
	{
		throw new NotImplementedException( 'QnAExternalUser is not implemented' );
	}

	public function qa_get_login_links( $relative_url_prefix, $redirect_back_to_url )
	{
		throw new NotImplementedException( 'QnAExternalUser is not implemented' );
	}

	public function qa_get_logged_in_user()
	{
		throw new NotImplementedException( 'QnAExternalUser is not implemented' );
	}

	public function qa_get_user_email( $userid )
	{
		throw new NotImplementedException( 'QnAExternalUser is not implemented' );
	}

	public function qa_get_userids_from_public( $publicusernames )
	{
		throw new NotImplementedException( 'QnAExternalUser is not implemented' );
	}

	public function qa_get_public_from_userids( $userids )
	{
		throw new NotImplementedException( 'QnAExternalUser is not implemented' );
	}

	public function qa_get_logged_in_user_html( $logged_in_user, $relative_url_prefix )
	{
		throw new NotImplementedException( 'QnAExternalUser is not implemented' );
	}

	public function qa_get_users_html( $userids, $should_include_link, $relative_url_prefix )
	{
		throw new NotImplementedException( 'QnAExternalUser is not implemented' );
	}

	public function qa_avatar_html_from_userid( $userid, $size, $padding )
	{
		throw new NotImplementedException( 'QnAExternalUser is not implemented' );
	}

	public function qa_user_report_action( $userid, $action )
	{
		throw new NotImplementedException( 'QnAExternalUser is not implemented' );
	}

	public function getConfig()
	{
		throw new NotImplementedException( 'QnAExternalUser is not implemented' );
	}

	public function getTemplate()
	{
		throw new NotImplementedException( 'QnAExternalUser is not implemented' );
	}
}