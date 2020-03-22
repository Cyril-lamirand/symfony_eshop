<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(TranslatorInterface $translator)
    {
        // Translation EN -> FR
        $local_welcome = $translator->trans('Welcome on my Symfony project !');
        $local_info1 = $translator->trans('It\'s my IDE not yours !');
        $local_info2 = $translator->trans('Note that you can use the website without using the URL, it\'s only for your information.');

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'loc_welcome' => $local_welcome,
            'loc_info1' => $local_info1,
            'loc_info2' => $local_info2,
        ]);
    }
}
