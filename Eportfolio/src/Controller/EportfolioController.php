<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EportfolioController extends AbstractController
{
    #[Route('/eportfolio', name: 'app_eportfolio')]
    public function index(): Response
    {
        return $this->render('eportfolio/index.html.twig', [
            'controller_name' => 'EportfolioController',
        ]);
    }

    #[Route('/home', name: 'app_eportfolio_home')]
    public function home(): Response
    {
        return $this->render('eportfolio/home.html.twig', [
            'controller_name' => 'EportfolioController',
        ]);
    }

    #[Route('/loisirs', name: 'app_eportfolio_loisirs')]
    public function loisirs(): Response
    {
        return $this->render('eportfolio/loisirs.html.twig', [
            'controller_name' => 'EportfolioController',
        ]);
    }

    #[Route('/cv', name: 'app_eportfolio_cv')]
    public function cv(): Response
    {
        return $this->render('eportfolio/cv.html.twig', [
            'controller_name' => 'EportfolioController',
        ]);
    }

    #[Route('/portfolio', name: 'app_eportfolio_portfolio')]
    public function portfolio(): Response
    {
        return $this->render('eportfolio/portfolio.html.twig', [
            'controller_name' => 'EportfolioController',
        ]);
    }

###mentions légales

    #[Route('/contact', name: 'app_eportfolio_contact')]
    public function contact(): Response
    {
        return $this->render('eportfolio/contact.html.twig', [
            'controller_name' => 'EportfolioController',
        ]);
    }

    #[Route('/send-email', name: 'app_eportfolio_send_email', methods: ['POST'])]
    public function sendEmail(Request $request, MailerInterface $mailer): Response
    {
        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $subject = $request->request->get('subject');
        $messageContent = $request->request->get('message');

        if (!$name || !$email || !$subject || !$messageContent) {
            return new Response('Tous les champs sont requis.', 400);
        }

        try {
            $emailMessage = (new Email())
                ->from($email)
                ->to('titouan.flch@gmail.com')
                ->subject($subject)
                ->text("Nom: $name\n\n$messageContent");

            $mailer->send($emailMessage);

            return new Response('Email envoyé avec succès.', 200);
        } catch (\Exception $e) {
            return new Response('Une erreur est survenue lors de l\'envoi de l\'email.', 500);
        }
    }
    #[Route('/download/cv/{format}', name: 'download_cv')]
    public function downloadCV(string $format): BinaryFileResponse
    {
        $projectDir = $this->getParameter('kernel.project_dir');
        
        if ($format === 'pdf') {
            $file = $projectDir . '/public/download/cvTitouanFloch.pdf';
            $fileName = 'cvTitouanFloch.pdf';
        } else if ($format === 'docx') {
            $file = $projectDir . '/public/download/cvTitouanFloch.docx';
            $fileName = 'cvTitouanFloch.docx';
        } else {
            throw $this->createNotFoundException('Format non supporté');
        }

        $response = new BinaryFileResponse($file);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $fileName
        );

        return $response;
    }
}
