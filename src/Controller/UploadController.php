<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\CurlHttpClient;

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
        $path = $this->getParameter('file_directory');

        if ($form->isSubmitted() && $form->isValid()) {

            $file  = $form->get('file')->getData();
            $apiUrl=$this->getParameter('API_URL');

            $client = new CurlHttpClient();

            $file  = $form->get('file')->getData();
            $apiUrl=$this->getParameter('API_URL');

            $fields = [
                'file' => new DataPart($path.'1617820206_proyecto Apis.jpg', 'test.csv', 'text/csv'),
            ];


            $formData = new FormDataPart($fields);



            $response=$client->request('POST', $apiUrl.'/api/employee/import', ['body' => $formData->bodyToString()]);
            // to do try catch and display error or succes

        }

        return $this->render('upload/index.html.twig', [
            'controller_name' => 'UploadController',
            'form' => $form->createView(),
        ]);
    }
}
