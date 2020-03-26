<?php

namespace App\Controller;

use App\Entity\Job;
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

class JobController extends AbstractController
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
     * @Route("/jobs", name="job_index", methods={"GET"})
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function index()
    {
        $jobs = $this->getDoctrine()->getRepository(Job::class)->findAll();
        $data = $this->serializer->normalize($jobs, null, ['groups' => 'jobs']);
        $jsonContent = $this->serializer->serialize($data, 'json');
        $response = new Response($jsonContent, 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/jobs", name="job_create", methods={"POST"})
     * @param Request $request
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function create(Request $request){

        $job = new Job();
        $job->setTitle($request->get('title'));

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($job);
        $manager->flush();

        $data = $this->serializer->normalize($job);
        $jsonContent = $this->serializer->serialize($data, 'json');
        $response = new Response($jsonContent, 201);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("jobs/{job}", name="job_show", methods={"GET"}, requirements={"job"="\d+"})
     * @param Request $request
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function Show(Request $request){

        $job = $this->getDoctrine()->getRepository(Job::class)->find($request->get('job'));

        $data = $this->serializer->normalize($job, null, ['groups' => 'job']);
        $jsonContent = $this->serializer->serialize($data, 'json');
        $response = new Response($jsonContent, 200);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/jobs/{job}", name="job_edit", methods={"POST"}, requirements={"job"="\d+"})
     * @param Request $request
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function edit(Request $request){

        $job = $this->getDoctrine()->getRepository(Job::class)->find($request->get('job'));

        if($request->get('title')){
            $job->setTitle($request->get('title'));
        }
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($job);
        $manager->flush();

        $data = $this->serializer->normalize($job, null, ['groups' => 'job']);
        $jsonContent = $this->serializer->serialize($data, 'json');
        $response = new Response($jsonContent, 201);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/jobs/{job}", name="job_delete", methods={"DELETE"}, requirements={"job"="\d+"})
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request){
        $job = $this->getDoctrine()->getRepository(Job::class)->find($request->get('job'));
        $this->getDoctrine()->getManager()->remove($job);
        $this->getDoctrine()->getManager()->flush();
        return new Response(null,200);
    }
}
