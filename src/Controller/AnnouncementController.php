<?php
// src/Controller/AnnouncementController.php
namespace App\Controller;

use App\Entity\Announcement;
use App\Security\AnnouncementVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
//for use form
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\Type\Announcement\AnnouncementType;


class AnnouncementController extends AbstractController
{

    /**
     * @Route("/announcement/create", name="create_announcement")
     */
    public function CreateAnnouncement(Request $request)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');//Визначає чи зареєстрований корирстувач

        /** @var \App\Entity\User $user */
        $user = $this->getUser(); //бере дані який саме користувач зайшов - для майбутнього запису його id для ManyToOne

        $announcement = new Announcement(); //Створює чистий обєкт

        $announcement -> setUser($user); // Записує дані отриманого юзера в таблицю Announcement

        $formCreateAnnouncement = $this->createForm(AnnouncementType::class, $announcement);

        $formCreateAnnouncement->handleRequest($request);

        if ($formCreateAnnouncement->isSubmitted() && $formCreateAnnouncement->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($announcement);

            $entityManager->flush();

            $id = $announcement->getId();

            $url = $this->generateUrl(
                'edit_announcement'
                //'edit_announcement',
                //array('id' => $id)
            );
            //return $this->redirect($url);
        }

        return $this->render('Announcement/create_announcement.html.twig', [
            'formCreateAnnouncement' => $formCreateAnnouncement->createView(),
        ]);

    }









    /**
     * @Route("/announcements", name="announcements_all")
     */
    public function allAnnouncements(Request $request)
    {

        return $this->render('Announcement/announcements.html.twig', [

        ]);

    }

    /**
     * @Route("/announcement/view", name="view_announcement")
     */
    public function ViewAnnouncement(Request $request)
    {

        return $this->render('Announcement/view_announcement.html.twig', [

        ]);

    }









    /**
     * @Route("/announcement/edit/{id}", name="edit_announcement")
     */
    public function edit($id, Request $request)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');//Визначає чи зареєстрований корирстувач

        /** @var \App\Entity\User $user */
        $user = $this->getUser(); //бере дані який саме користувач зайшов - для майбутнього запису його id для ManyToOne

        $entityManager = $this->getDoctrine()->getManager();

        $announcement = $entityManager->getRepository(Announcement::class)->find($id);

        $this->denyAccessUnlessGranted(AnnouncementVoter::EDIT, $announcement);// застосування Voter

        $announcement -> setUser($user); // Записує дані отриманого юзера в таблицю Announcement

        $formCreateAnnouncement = $this->createForm(AnnouncementType::class, $announcement);

        $formCreateAnnouncement->handleRequest($request);

        if ($formCreateAnnouncement->isSubmitted() && $formCreateAnnouncement->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($announcement);

            $entityManager->flush();

            /*
            $id = $announcement->getId(); //Визначає id номер для того щоб потім його використати в свторенні url адреси для переадресації

            $url = $this->generateUrl(
                //'edit_announcement'
            'edit_announcement',
            array('id' => $id)
            );
            return $this->redirect($url);
            */

        }

        return $this->render('Announcement/edit_announcement.html.twig', [
            'yourEmail' => $user->getEmail(),
            'formEditAnnouncement' => $formCreateAnnouncement->createView(),
        ]);

    }


}
