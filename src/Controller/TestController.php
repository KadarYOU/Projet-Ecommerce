<?php

namespace App\Controller;


use App\Taxes\Detector;
use App\Taxes\Calculator;
use Cocur\Slugify\Slugify;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

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
  public function test(Request $request, $prenom, Environment $twig)
  {
    $html = $twig->render('hello.html.twig', [
      'prenom' => $prenom,
      'formateur1' => ['prenom' => 'Lior', 'nom' => 'Chamla'],
      'formateur2' => ['prenom' => 'Kadar', 'nom' => 'Youssouf']



    ]);



    return new Response($html);
  }
}
