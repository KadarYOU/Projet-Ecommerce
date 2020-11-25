<?php

namespace App\Controller;


use App\Taxes\Calculator;
use Cocur\Slugify\Slugify;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
  protected $calculator;

  public function __construct(Calculator $calculator)
  {
    $this->calculator = $calculator;
  }
  /**
   * @Route("/test/{prenom?World}", name="test")
   */
  public function test(Request $request, $prenom)
  {
    $tva = $this->calculator->calcul(100);

    $slugify = new Slugify();
    $slugify->Slugify("Hello World");
    dump($slugify);
    return new Response("Hello $prenom ");
  }
}
