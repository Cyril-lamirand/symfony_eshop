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
    public function index(Request $request, TranslatorInterface $translator)
    {
        $number = random_int(0, 100);
        $locale = $request->getLocale();
        $translated = $translator->trans('Symfony is great !');
        $translated2 = $translator->trans('The random number');
        $translated3 = $translator->trans('You can change the language here');
        $translated4 = $translator->trans('Laboratory');
        return $this->render('lucky/index.html.twig', [
            'controller_name' => 'LuckyController',
            'lucky_number' => $number,
            'language' => $locale,
            'trans' => $translated,
            'trans2' => $translated2,
            'trans3' => $translated3,
            'loc_laboratory' => $translated4,
        ]);
    }
}
