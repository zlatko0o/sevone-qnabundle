<?php

namespace SevOne\QnABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SevOneQnaBundle:Default:index.html.twig', array('name' => $name));
    }
}
