<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Services;
use App\Form\ContactType;
use App\Repository\AboutRepository;
use App\Repository\CategoryRepository;
use App\Repository\ContactInfoRepository;
use App\Repository\HomeRepository;
use App\Repository\ServicesRepository;
use App\Repository\SlideRepository;
use App\Repository\ThumbImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Turbo\Stream\TurboStreamResponse;
use Symfony\UX\Turbo\TurboBundle;

class HomeController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(
        ServicesRepository $services,
        HomeRepository $home,
        SlideRepository $slide,
        ThumbImageRepository $thumb,
        Request $request
    ): Response {

        $services = $services->findAll();
        $bestServices = $this->em->getRepository(Services::class)->findByIsBest(1);
        $homes = $home->findAll();
        $thumbs = $thumb->findAll();
        $footerYear = new \DateTime();
        $lastSlides = $slide->findLastTreeImages();
        $gallerieImages = $slide->findImagesInFirstPage();

        return $this->render('home/index.html.twig', [
            'services' => $services,
            'homes' => $homes,
            'thumbs' => $thumbs,
            'footerYear' => $footerYear,
            'slides' => $lastSlides,
            'bestServices' => $bestServices,
            'gallerieImages' => $gallerieImages
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
    public function services(ServicesRepository $services, CategoryRepository $category): Response
    {
        $services = $services->findAll();
        $categorys = $category->findAll();

        return $this->render('home/service.html.twig', [
            'services' => $services,
            'categorys' => $categorys
        ]);
    }

    #[Route('/activites/{slug}', name: 'app_services_show', methods: ['GET'])]
    public function serviceShow(string $slug, ServicesRepository $services, SlideRepository $slide): Response
    {
        $links = $services->findAll();
        $services = $services->findOneBy(['slug' => $slug]);

        return $this->render('home/service_show.html.twig', [
            'services' => $services,
            'links' => $links,
        ]);
    }


    #[Route('/galerie', name: 'app_galerie')]
    public function gallerie(SlideRepository $slides): Response
    {
        $slides = $slides->findAll();
        return $this->render('home/galerie.html.twig', [
            'slides' => $slides,
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(
        Request $request,
        EntityManagerInterface $manager,
        MailerInterface $mailer,
        ContactInfoRepository $contactInfo
    ): Response {
        $contactDescription = $contactInfo->findAll();

        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        $message = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $manager->persist($contact);

            $adresse = $contact->getEmail();

            $email = (new TemplatedEmail())
                ->from('contact@gwadaexcursion.fr')
                ->to('contact@gwadaexcursion.fr')
                ->subject($contact->getSubject())
                ->htmlTemplate('emails/mail.html')
                ->context([
                    'prenom' => $contact->getFirstName(),
                    'nom' => $contact->getLastName(),
                    'contact' => $adresse,
                    'subject' => $contact->getSubject(),
                    'message' => $contact->getMessage()
                ]);

            $mailer->send($email);

            $manager->flush();

            $this->addFlash('success', 'Votre message à bien été envoyé');

            return $this->redirectToRoute('app_contact');
        }

        $response = new Response(null, $form->isSubmitted() ? 422 : 200);

        return $this->render('home/contact.html.twig', [
            'contactForm' => $form->createView(),
            'contactDescription' => $contactDescription,
        ], $response);
    }

    #[Route('/faqs', name: 'app_faq')]
    public function faq(): Response
    {
        return $this->render('home/faq.html.twig');
    }

    #[Route('/mentions-legales', name: 'app_rgpd')]
    public function rgpd(): Response
    {
        return $this->render('home/cookie.html.twig');
    }
}
