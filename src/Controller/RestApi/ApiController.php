<?php

namespace App\Controller\RestApi;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use  Doctrine\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\FOSRestBundle;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\View\View;

use App\Entity\Contact;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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

    /**
     * Import file
     * @Rest\Post("/employee/import")
     */
    public function importFile(Request $request,ValidatorInterface $validator)
    {
        $file = $request->files->get('file');

        if(empty($file)){
            return$this->handleView($this->view(['message' => 'file not found']));
        }

        $entityManager = $this->getDoctrine()->getManager();
        $name =time() . '_' . $file->getClientOriginalName();
        $path = $this->getParameter('file_directory');
        // upload  file
        $file->move($path, $name);

        // 3d part read file
        $context = [
            CsvEncoder::DELIMITER_KEY => ',',
            CsvEncoder::ENCLOSURE_KEY => '"',
            CsvEncoder::ESCAPE_CHAR_KEY => '\\',
            CsvEncoder::KEY_SEPARATOR_KEY => ';',
        ];

        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $serializer = $this->get('serializer');
        // get data
        $data = $serializer->decode(file_get_contents($path.$name), 'csv', $context);


        if(!empty($data)){
            foreach ($data as $ligne ){

                $emailConstraint = new Assert\Email();
                $emailConstraint->message = 'Invalid email address';

                // validate email
                $errors = $validator->validate(
                   $ligne['E Mail'],
                    $emailConstraint
                );

                if($errors->count()>0){
                    // to do add errors return
                    continue;
                }

                // create new employee
                $employeContact=new Contact();
                $employeContact->setFirstName($ligne['First Name']);
                $employeContact->setUserName($ligne['User Name']);
                $employeContact->setMidleName($ligne['Middle Initial']);
                $employeContact->setLastName($ligne['Last Name']);
                $employeContact->setNamePrefix($ligne['Name Prefix']);
                $employeContact->setDateJoin(\DateTime::createFromFormat('d/m/yy', $ligne['Date of Joining'])->format('y-m-d'));
                $employeContact->setTimeBirth(\DateTime::createFromFormat('h:m:s', date('h:m:s', strtotime($ligne['Time of Birth']))));
                $employeContact->setAgeBirth($ligne['Age in Yrs.']);
                $employeContact->setAgeInCompany($ligne['Age in Company (Years)']);
                $employeContact->setCity($ligne['City']);
                $employeContact->setEmail($ligne['E Mail']);
                $employeContact->setCountry($ligne['County']);
                $employeContact->setGender($ligne['Gender']);
                $employeContact->setPlace($ligne['Place Name']);
                $employeContact->setRegion($ligne['Region']);
                $employeContact->setZip($ligne['Zip']);
                $employeContact->setPhone($ligne['Phone No. ']);
                $employeContact->setDateBirth(\DateTime::createFromFormat('h:m:s', date('h:m:s', strtotime($ligne['Date of Birth']))));
                $entityManager->persist($employeContact);
                $entityManager->flush();
            }
            return$this->handleView($this->view(['message' => 'data imported ']));
        }
             else {
            return$this->handleView($this->view(['message' => 'cant read csv file ']));
        }
    }
}



