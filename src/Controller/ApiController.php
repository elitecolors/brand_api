<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\FOSRestBundle;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\View\View;
use App\Entity\Contact;
use App\Form\EmployeType;

class ApiController extends AbstractFOSRestController
{

    /**
     * Retrieves an employe contact by id resource
     * @param ParamFetcher $paramFetcher
     * @Rest\QueryParam(
     *     name="output_type",
     *     default="json",
     *     description="Output type"
     * )
     * @Rest\View()
     * @Rest\Get("/employee/get/{id}")
     */
    public function getEmployee(ParamFetcher $paramFetcher,Request $request)
    {
        $repository=$this->getDoctrine()->getRepository(Contact::class);
        $employee = $repository->findById($request->get('id'));
        $format='json';
        // set format if not json
        if($paramFetcher->get('output_type')=='xml'){
            $format='xml';
        }

        // to do add try catch
        if (empty($employee)) {
            return$this->handleView($this->view(['message' => 'Employee not found'])->setFormat($format));
        }
        return$this->handleView($this->view($employee)->setFormat($format));

    }
    /**
     * Lists all contact.
     * @param ParamFetcher $paramFetcher
     * @Rest\QueryParam(
     *     name="output_type",
     *     default="json",
     *     description="Output type"
     * )
     * @Rest\QueryParam(
     *     name="offset",
     *     default=0,
     *     description="offset"
     * )
     * @Rest\QueryParam(
     *     name="length",
     *     default=1,
     *     description="offset"
     * )
     * @Rest\QueryParam(
     *     name="search",
     *     description="search"
     * )
     * @Rest\View()
     * @Rest\Get("/employee/list")
     *
     * @return Response
     */
    public function listAction(ParamFetcher $paramFetcher)
    {

        $repository = $this->getDoctrine()->getRepository(Contact::class);
        $format='json';
        // set format if not json
        if($paramFetcher->get('output_type')=='xml'){
            $format='xml';
        }

        $contact=$repository->findInAll($paramFetcher->get('search'),$paramFetcher->get('length'),$paramFetcher->get('offset'));


        if(empty($contact)){
                return$this->handleView($this->view(['message' => 'data not found'])->setFormat($format));
        }

        return $this->handleView($this->view($contact)->setFormat($format));
    }

}



