<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UploadController extends AbstractController
{
    /**
     * @Route("/employee/upload", name="upload")
     */
    public function index (Request $request): Response
    {

        $defaultData = [];
        $form = $this->createFormBuilder($defaultData)
            ->add('file', FileType::class)
            ->add('send', SubmitType::class)
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            var_dump($data);
            die();
        }


        return $this->render('upload/index.html.twig', [
            'controller_name' => 'UploadController',
            'form' => $form->createView(),
        ]);
    }
}
