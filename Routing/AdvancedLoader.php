<?php

namespace SevOne\QnABundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class AdvancedLoader extends Loader
{
	public function load( $resource, $type = null )
	{
		$collection = new RouteCollection();

		$resource = '@SevOneQnaBundle/Resources/config/routing.yml';
		$type     = 'yaml';

		$importedRoutes = $this->import( $resource, $type );

		$collection->addCollection( $importedRoutes );

		return $collection;
	}

	public function supports( $resource, $type = null )
	{
		return 'advanced_extra' === $type;
	}
}