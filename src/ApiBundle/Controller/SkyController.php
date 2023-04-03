<?php

namespace App\ApiBundle\Controller;

use App\ApiBundle\Serializer\Normalizer;
use App\SkyBundle\Entity\Star;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * Class SkyController
 * @package App\ApiBundle\Controller
 * @Route("/api", name="star_api")
 */
class SkyController extends AbstractController
{

    private $normalizer;

    public function __construct(Normalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @Route("/create", methods={"POST"}, name="create_star")
     */
    public function create(Request $request)
    {
        $parameters = json_decode($request->getContent(), true);
        $em = $this->container->get('doctrine')->getManager();

        if ($parameters['name'] === null
            || $parameters['galaxy'] === null
            || $parameters['radius'] === null
            || $parameters['temperature'] === null
            || $parameters['rotation_frequency'] === null
            || $parameters['atoms_found'] === null
        ) {
            return new Response('', 400, ['content-type' => 'application/json']);
        }

        $star = new Star;

        $star->setName($parameters['name']);
        $star->setGalaxy($parameters['galaxy']);
        $star->setRadius($parameters['radius']);
        $star->setTemperature($parameters['temperature']);
        $star->setRotationFrequency($parameters['rotation_frequency']);
        $star->setAtomsFound($parameters['atoms_found']);

        try {
            $em->persist($star);
            $em->flush();
            $data = $this->normalizer->normalize($star, 'basic');
            return new Response($data);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), 409, ['content-type' => 'application/json']);
        }

    }

    /**
     * @Route("/read/{starId}", requirements={"starId": "\d+"}, methods={"GET"}, name="get_star")
    */
    public function read(int $starId)
    {
        $em = $this->container->get('doctrine')->getManager();
        $star = $em->getRepository(Star::class)
            ->findOneById($starId);

        if (!$star) {
            return new Response('', 404, ['content-type' => 'application/json']);
        }

        $data = $this->normalizer->normalize($star, 'basic');
        return new Response($data);
    }

    /**
     * @Route("/update/{starId}", requirements={"starId": "\d+"}, methods={"PATCH"}, name="update_star")
     */
    public function update(Request $request, int $starId)
    {
        $parameters = json_decode($request->getContent(), true);
        $em = $this->container->get('doctrine')->getManager();
        $star = $em->getRepository(Star::class)
            ->findOneById($starId);

        if (!$star) {
            return new Response('', 404, ['content-type' => 'application/json']);
        }

        if ($parameters['name'] !== null) {
            $star->setName($parameters['name']);
        }
        if ($parameters['galaxy'] !== null) {
            $star->setGalaxy($parameters['galaxy']);
        }
        if ($parameters['radius'] !== null) {
            $star->setRadius($parameters['radius']);
        }
        if ($parameters['temperature'] !== null) {
            $star->setTemperature($parameters['temperature']);
        }
        if ($parameters['rotation_frequency'] !== null) {
            $star->setRotationFrequency($parameters['rotation_frequency']);
        }
        if ($parameters['atoms_found'] !== null) {
            $star->setAtomsFound($parameters['atoms_found']);
        }

        try {
            $em->persist($star);
            $em->flush();
            $data = $this->normalizer->normalize($star, 'basic');
            return new Response($data);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), 409, ['content-type' => 'application/json']);
        }
    }

    /**
     * @Route("/delete/{starId}", requirements={"starId": "\d+"}, methods={"DELETE"}, name="delete_star")
     */
    public function delete(int $starId)
    {
        $em = $this->container->get('doctrine')->getManager();
        $star = $em->getRepository(Star::class)
            ->findOneById($starId);

        if (!$star) {
            return new Response('', 404, ['content-type' => 'application/json']);
        }

        $em->remove($star);
        $em->flush();

        return new JsonResponse();
    }

    /**
     * @Route("/uniqueStars", methods={"GET"}, name="unique_stars")
     */
    public function uniqueStars(Request $request)
    {
        $foundIn = $request->query->get('foundIn');
        $notFoundIn = $request->query->get('notFoundIn');
        $atomsList = $request->query->get('atomsList');
        $sortBy = $request->query->get('sortBy'); // size, temperature
        $viewType = $request->query->get('viewType');

        if (($foundIn === null
                || $notFoundIn === null
                || $atomsList === null
                || $sortBy === null)
            && ($sortBy === 'size' || $sortBy === 'temperature')
        ) {
            return new Response('', 400, ['content-type' => 'application/json']);
        }

        $em = $this->container->get('doctrine')->getManager();
        $stars = $em->getRepository(Star::class)
            ->findUniqueStars($foundIn, $notFoundIn, $atomsList, $sortBy);

        $data = [];
        foreach ($stars as $star) {
            $data[] = json_decode($this->normalizer->normalize($star, $viewType));
        }

        return new JsonResponse($data);
    }


}