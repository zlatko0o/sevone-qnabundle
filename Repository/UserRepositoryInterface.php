<?php

namespace SevOne\QnABundle\Repository;

interface UserRepositoryInterface
{
	public function getUsersByUsernames( $username );

	public function getUsersByIds( $ids );

	public function find();

}