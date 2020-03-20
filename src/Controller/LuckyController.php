<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;

class LuckyController extends AbstractController
{
    /**
     * @Route("/lucky", name="lucky")
     */
    public function index(Request $request)
    {
        $number = random_int(0, 100);
        $locale = $request->getLocale();
        return $this->render('lucky/index.html.twig', [
            'controller_name' => 'LuckyController',
            'lucky_number' => $number,
            'language' => $locale,
        ]);
    }
}
