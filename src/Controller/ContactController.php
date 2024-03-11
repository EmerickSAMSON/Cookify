<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $data = new ContactDTO;
        $form = $this->createForm(ContactType::class, $data);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $email = (new TemplatedEmail())
                    ->to($data->service)
                    ->from($data->email)
                    ->subject("Demande de contact")
                    ->htmlTemplate('emails/contact.html.twig')
                    ->context(['data' => $data]);

                $mailer->send($email);

                $this->addFlash('success', "Votre message a bien été envoyé");
                $this->redirectToRoute("contact");
            } catch (\Throwable $th) {
                $this->addFlash('danger', "impossible d'envoyer votre message");
            }
        }
        return $this->render('contact/contact.html.twig', [
            "form" => $form,
            "data" => $data
        ]);
    }
}
