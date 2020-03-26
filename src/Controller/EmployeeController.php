<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\Job;
use App\Repository\JobRepository;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EmployeeController extends AbstractController
{
    public  $serializer;

    public function __construct()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer($classMetadataFactory)];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @Route("/employees", name="employee_index", methods={"GET"})
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function index()
    {
        $employees = $this->getDoctrine()->getRepository(Employee::class)->findAll();
        $data = $this->serializer->normalize($employees, null, ['groups' => 'employees']);
        $jsonContent = $this->serializer->serialize($data, 'json');
        $response = new Response($jsonContent, 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/employees", name="employee_create", methods={"POST"})
     * @param Request $request
     * @param JobRepository $jobRepository
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Exception
     */
    public function create(Request $request, JobRepository $jobRepository){

        $employee = new Employee();
        $employee->setFirstname($request->get('firstname'));
        $employee->setLastname($request->get('lastname'));
        $date = new \DateTime($request->get('employement_date'));
        $employee->setEmployementDate($date);
        $job = $jobRepository->find($request->get('job_id'));
        $employee->setJob($job);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($employee);
        $manager->flush();

        $data = $this->serializer->normalize($employee, null, ['groups' => 'employees']);
        $jsonContent = $this->serializer->serialize($data, 'json');
        $response = new Response($jsonContent, 201);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("employees/{employee}", name="employees_show", methods={"GET"}, requirements={"employee"="\d+"})
     * @param Request $request
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function Show(Request $request){

        $employee = $this->getDoctrine()->getRepository(Employee::class)->find($request->get('employee'));

        $data = $this->serializer->normalize($employee, null, ['groups' => 'employees']);
        $jsonContent = $this->serializer->serialize($data, 'json');
        $response = new Response($jsonContent, 200);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/employees/{employee}", name="employee_edit", methods={"POST"}, requirements={"employee"="\d+"})
     * @param Request $request
     * @param JobRepository $jobRepository
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Exception
     */
    public function edit(Request $request, JobRepository $jobRepository){

        $employee = $this->getDoctrine()->getRepository(Employee::class)->find($request->get('employee'));

        if($request->get('firstname')){
            $employee->setFirstname($request->get('firstname'));
        }
        if($request->get('lastname')){
            $employee->setLastname($request->get('lastname'));
        }
        if($request->get('employement_date')){
            $date = new \DateTime($request->get('employement_date'));
            $employee->setEmployementDate($date);
        }
        if($request->get('job_id')){
            $job = $jobRepository->find($request->get('job_id'));
            $employee->setJob($job);
        }
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($employee);
        $manager->flush();

        $data = $this->serializer->normalize($employee, null, ['groups' => 'employees']);
        $jsonContent = $this->serializer->serialize($data, 'json');
        $response = new Response($jsonContent, 201);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/employees/{employee}", name="employee_delete", methods={"DELETE"}, requirements={"employee"="\d+"})
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request){
        $employee = $this->getDoctrine()->getRepository(Employee::class)->find($request->get('employee'));
        $this->getDoctrine()->getManager()->remove($employee);
        $this->getDoctrine()->getManager()->flush();
        return new Response(null,200);
    }
}
