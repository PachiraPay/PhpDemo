<?php
// src/Controller/AdvertController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment; 

class HomeController extends AbstractController
{
   /**
   * @Route("/", name="home_index")
   */
   public function Index()
  {

    return $this->render('Home/index.html.twig');
  }

}