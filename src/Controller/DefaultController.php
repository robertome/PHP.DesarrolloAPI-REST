<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 *
 * @package App\Controller
 */
class DefaultController extends AbstractController
{

    /**
     * @Route(path="", name="index")
     * index
     * @return Response
     */
    public function index(): Response
    {
        return $this->redirect('./api-docs/index.html');
    }

}
