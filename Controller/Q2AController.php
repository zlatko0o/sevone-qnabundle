<?php

namespace SevOne\QnABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Q2AController extends Controller
{
	public function indexAction( Request $request, $path )
	{
		$response = $this->get( 'sevone.qnabundle.q2aservice' )->getResponse( $path );

		//we should not render template when we have ajax calls
		if( $request->isXmlHttpRequest() )
			return new Response( $response );

		//if we need rss response
		if( strpos( $path, '.rss' ) !== false )
			return new Response( $response );

		$data = [
			'content' => $response,
			'module' => 'qna',
			'pageTitle' =>  $this->get( 'sevone.qnabundle.q2aservice' )->pageTitle( $request )
		];

		return $this->render( $this->get( 'sevone.qnabundle.external_user' )->getTemplate(), $data );
	}
}
