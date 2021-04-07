<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\FOSRestBundle;
use FOS\RestBundle\View\View;
use App\Entity\Contact;

class ApiController extends AbstractFOSRestController
{

    /**
     * Retrieves an employe contact by id resource
     * @Rest\Get("/employee/get/{id}")
     */
    public function getEmployee(int $id): View
    {
        $repository=$this->getDoctrine()->getRepository(Contact::class);
        $employee = $repository->findById($id);

        // In case our GET was a success we need to return a 200 HTTP OK response with the request object
        return View::create($employee, Response::HTTP_OK);
    }
    /**
     * Lists all contact.
     * @Rest\Get("/employee/list")
     *
     * @return Response
     */
    public function listAction()
    {
        $repository = $this->getDoctrine()->getRepository(Contact::class);
        $contact = $repository->findall();
        return $this->handleView($this->view($contact));
    }

}


