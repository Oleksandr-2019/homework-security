<?php
// src/Controller/AdminStatisticController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminStatisticController extends AbstractController
{
    /**
     * @Route("/admin/statistic", name="admin_statistic")
     */
    public function adminStatistic(Request $request)
    {

        return $this->render('Admin/admin_statistic.html.twig', [

        ]);

    }
}
