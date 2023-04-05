<?php

namespace App\ApiBundle\Controller;

use App\ApiBundle\Serializer\Normalizer;
use App\SkyBundle\Entity\Atom;
use App\SkyBundle\Entity\Star;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\ApiBundle\Model\Star as APIStar;
use App\ApiBundle\Model\UniqueStar as APIUniqueStar;

/**
 * Class SkyController
 * @package App\ApiBundle\Controller
 * @Route("/api", name="star_api")
 */
class SkyController extends AbstractController
{

    private $normalizer;

    /**
     * @param Normalizer $normalizer
     */
    public function __construct(Normalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @Route("/create", methods={"POST"}, name="create_star")
     * @OA\Response(
     *     response=200,
     *     description="Create star",
     *     @Model(type=APIStar::class)
     * )
     *
     * @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *      @OA\Property(
     *          property="name",
     *          type="string",
     *          description="Star name",
     *          example="Star 1"
     *      ),
     *      @OA\Property(
     *          property="galaxy",
     *          type="string",
     *          description="Galaxy name",
     *          example="Galaxy 1"
     *      ),
     *      @OA\Property(
     *          property="radius",
     *          type="integer",
     *          description="Star radius",
     *          example="2342342"
     *      ),
     *      @OA\Property(
     *          property="temperature",
     *          type="integer",
     *          description="Star temperature",
     *          example="3456"
     *      ),
     *     @OA\Property(
     *          property="rotation_frequency",
     *          type="number",
     *          description="Star rotation frequency",
     *          example="0.44442224"
     *      ),
     *      @OA\Property(
     *          type="array",
     *          property="atoms_found",
     *          description="Atoms found in star",
     *          @OA\Items(
     *             type="integer"
     *          )
     *       )
     *    )
     * )
     *
     * @Security(name="Sky-authorization")
     * @OA\Tag(name="star")
     */
    public function create(Request $request)
    {
        $parameters = json_decode($request->getContent(), true);
        $em = $this->container->get('doctrine')->getManager();

        if (!isset($parameters['name'])
            || !isset($parameters['galaxy'])
            || !isset($parameters['radius'])
            || !isset($parameters['temperature'])
            || !isset($parameters['rotation_frequency'])
            || (!isset($parameters['atoms_found']) || !is_array($parameters['atoms_found']))
        ) {
            return new Response('', 400, ['content-type' => 'application/json']);
        }

        $star = new Star;

        $star->setName($parameters['name']);
        $star->setGalaxy($parameters['galaxy']);
        $star->setRadius($parameters['radius']);
        $star->setTemperature($parameters['temperature']);
        $star->setRotationFrequency($parameters['rotation_frequency']);

        foreach ($parameters['atoms_found'] as $atomValue) {
            $atom = new Atom;
            $atom->setValue($atomValue);
            $atom->addStar($star);
            $em->persist($atom);
        }

        try {
            $em->persist($star);
            $em->flush();
            $data = $this->normalizer->normalize($star, 'custom');
            return new Response($data);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), 409, ['content-type' => 'application/json']);
        }

    }

    /**
     * @Route("/read/{starId}", requirements={"starId": "\d+"}, methods={"GET"}, name="get_star")
     *
     * @OA\Response(
     *     response=200,
     *     description="Get star",
     *     @Model(type=APIStar::class)
     * )
     *
     * @Security(name="Sky-authorization")
     * @OA\Tag(name="star")
    */
    public function read(int $starId)
    {
        $em = $this->container->get('doctrine')->getManager();
        $star = $em->getRepository(Star::class)
            ->findOneById($starId);

        if (!$star) {
            return new Response('', 404, ['content-type' => 'application/json']);
        }

        $data = $this->normalizer->normalize($star, 'custom');
        return new Response($data);
    }

    /**
     * @Route("/update/{starId}", requirements={"starId": "\d+"}, methods={"PATCH"}, name="update_star")
     *
     * @OA\Response(
     *     response=200,
     *     description="Update star",
     *     @Model(type=APIStar::class)
     * )
     *
     * @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *      @OA\Property(
     *          property="name",
     *          type="string",
     *          description="Star name",
     *          example="Star 1"
     *      ),
     *      @OA\Property(
     *          property="galaxy",
     *          type="string",
     *          description="Galaxy name",
     *          example="Galaxy 1"
     *      ),
     *      @OA\Property(
     *          property="radius",
     *          type="integer",
     *          description="Star radius",
     *          example="2342342"
     *      ),
     *      @OA\Property(
     *          property="temperature",
     *          type="integer",
     *          description="Star temperature",
     *          example="3456"
     *      ),
     *     @OA\Property(
     *          property="rotation_frequency",
     *          type="number",
     *          description="Star rotation frequency",
     *          example="0.44442224"
     *      ),
     *      @OA\Property(
     *          type="array",
     *          property="atoms_found",
     *          description="Atoms found in star",
     *          @OA\Items(
     *             type="integer"
     *          )
     *       )
     *    )
     * )
     *
     * @Security(name="Sky-authorization")
     * @OA\Tag(name="star")
     */
    public function update(Request $request, int $starId)
    {
        $parameters = json_decode($request->getContent(), true);
        $em = $this->container->get('doctrine')->getManager();
        /** @var Star $star */
        $star = $em->getRepository(Star::class)
            ->findOneById($starId);

        if (!$star) {
            return new Response('', 404, ['content-type' => 'application/json']);
        }

        if (isset($parameters['name'])) {
            $star->setName($parameters['name']);
        }
        if (isset($parameters['galaxy'])) {
            $star->setGalaxy($parameters['galaxy']);
        }
        if (isset($parameters['radius'])) {
            $star->setRadius($parameters['radius']);
        }
        if (isset($parameters['temperature'])) {
            $star->setTemperature($parameters['temperature']);
        }
        if (isset($parameters['rotation_frequency'])) {
            $star->setRotationFrequency($parameters['rotation_frequency']);
        }
        if (isset($parameters['atoms_found'])) {

            $atoms = $star->getAtoms();
            foreach ($atoms as $atom) {
                $star->removeAtom($atom);
            }

            foreach ($parameters['atoms_found'] as $atomValue) {
                $atom = new Atom;
                $atom->setValue($atomValue);
                $atom->addStar($star);
                $em->persist($atom);
            }
        }

        try {
            $em->persist($star);
            $em->flush();
            $data = $this->normalizer->normalize($star, 'custom');
            return new Response($data);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), 409, ['content-type' => 'application/json']);
        }
    }

    /**
     * @Route("/delete/{starId}", requirements={"starId": "\d+"}, methods={"DELETE"}, name="delete_star")
     *
     * @OA\Response(
     *     response=200,
     *     description="Delete star"
     * )
     *
     * @Security(name="Sky-authorization")
     * @OA\Tag(name="star")
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
     *
     * @OA\Response(
     *     response=200,
     *     description="Get unique stars",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=APIUniqueStar::class, groups={"full"}))
     *     )
     * )
     *
     * @OA\Parameter(
     *     name="foundIn",
     *     in="query",
     *     description="Found in galaxy",
     *     @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     *     name="notFoundIn",
     *     in="query",
     *     description="Not found in galaxy",
     *     @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     *     name="sortBy",
     *     in="query",
     *     description="Sort by field",
     *     @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     *     name="viewType",
     *     in="query",
     *     description="View type for the response",
     *     @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     *     name="atomsList[]",
     *     in="query",
     *     description="Atoms list",
     *     required=true,
     *     @OA\Schema(
     *         type="array",
     *         @OA\Items(type="integer")
     *     )
     * )
     * @Security(name="Sky-authorization")
     * @OA\Tag(name="star")
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
                || !is_array($atomsList)
                || $sortBy === null)
            && ($sortBy === 'size' || $sortBy === 'temperature')
        ) {
            return new Response('', 400, ['content-type' => 'application/json']);
        }

        $atoms = array_map(function ($val){
            return (int)$val;
        }, $atomsList);

        $em = $this->container->get('doctrine')->getManager();
        $stars = $em->getRepository(Star::class)
            ->findUniqueStars($foundIn, $notFoundIn, $atoms, $sortBy);

        $data = [];
        foreach ($stars as $star) {
            $data[] = json_decode($this->normalizer->normalize($star, $viewType));
        }

        return new JsonResponse($data);
    }


}