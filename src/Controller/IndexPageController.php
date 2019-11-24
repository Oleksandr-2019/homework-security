<?php
// src/Controller/IndexPageController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class IndexPageController extends AbstractController
{

    /**
     * @Route("/", name="app_homepage")
     */
    public function index(Request $request)
    {

        return $this->render('Home/index.html.twig', [

        ]);

    }

}
