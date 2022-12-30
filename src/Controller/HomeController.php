<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\AboutRepository;
use App\Repository\HomeRepository;
use App\Repository\ServicesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ServicesRepository $services, HomeRepository $home): Response
    {
        $services = $services->findAll();
        $homes = $home->findAll();
        $footerYear = new \DateTime();

        return $this->render('home/index.html.twig', [
          'services' => $services,
          'homes' => $homes,
          'footerYear' => $footerYear,
        ]);
    }

    #[Route('/a-propos', name: 'app_about')]
    public function about(AboutRepository $abouts): Response
    {
        $abouts = $abouts->findAll();

        return $this->render('home/about.html.twig', [
          'abouts' => $abouts,
        ]);
    }

    #[Route('/activites', name: 'app_services')]
    public function services(ServicesRepository $services): Response
    {
        $services = $services->findAll();

        return $this->render('home/service.html.twig', [
          'services' => $services,
        ]);
    }

    #[Route('/activites/{slug}', name: 'app_services_show', methods: ['GET'])]
    public function serviceShow(string $slug, ServicesRepository $services): Response
    {
        $services = $services->findOneBy(['slug' => $slug]);
        // dd($services);
        return $this->render('home/service_show.html.twig', [
          'services' => $services,
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(
    Request $request,
    EntityManagerInterface $manager,
    MailerInterface $mailer
    ): Response {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        $message = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $manager->persist($contact);
            $manager->flush();

            // dd($contact);
            // $email = (new TemplatedEmail())
            // ->from($contact->getEmail())
            // ->to('contact@codeviral.fr')
            // ->subject($contact->getSubject())
            // ->htmlTemplate('email/welcome.html.twig')
            // ->context([
            //   'name' => $contact->getFirstName(),
            //   'message' => $contact->getMessage()
            // ]);

            $email = (new Email())
            ->from($contact->getEmail())
            ->to('contact@codeviral.fr')
            ->subject($contact->getSubject())
            ->html($contact->getMessage());

            $mailer->send($email);

            $this->addFlash('success', 'Votre message à bien été envoyé');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('home/contact.html.twig', [
          'form' => $form->createView(),
        ]);
    }

      #[Route('/faqs', name: 'app_faq')]
      public function faq(): Response
      {
          return $this->render('home/faq.html.twig');
      }
}