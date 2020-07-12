<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdvertRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;



class AdvertController extends AbstractController
{
    private $advertisementRepository;
    
    public function __construct(AdvertRepository $advertRepository)
    {
        $this->advertRepository = $advertRepository;
    }

     
    /**
     * @Route("/adverts/add", name="add_advertisement", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function createAdvert(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $label = $data['label'];
        $content = $data['content'];

        if (empty($label) || empty($content)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->advertRepository->saveAdvert($label, $content);

        return new JsonResponse(['status' => 'Advertisement created!'], Response::HTTP_CREATED);
    }

    /**
    * @Route("/adverts", name="get_all_advertisements", methods={"GET"})
    */
    public function getAllAdverts(): JsonResponse
    {
        $adverts = $this->advertRepository->findAll();
        $data = [];

        foreach ($adverts as $advert) {
            $data[] = [
                'id' => $advert->getId(),
                'label' => $advert->getLabel(),
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
    * @Route("/adverts/{id}", name="get_one_advertisement", methods={"GET"})
    */
    public function getAdvert($id, Request $request): JsonResponse
    {
        $advert = $this->advertRepository->findOneBy(['id' => $id]);
        $this->advertRepository->updateViews($advert);
        $data = json_decode($request->getContent(), true);
        $data = [
            'id' => $advert->getId(),
            'label' => $advert->getLabel(),
            'content' => $advert->getContent(),
            'views' => $advert->getViews(),
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }


    /**
     * @Route("/adverts/{id}", name="update_advertisement", methods={"PUT"})
     * @IsGranted("ROLE_USER")
     */
    public function update($id, Request $request): JsonResponse
    {
        $advert = $this->advertRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['label']) ? true : $advert->setLabel($data['label']);
        empty($data['content']) ? true : $advert->setContent($data['content']);


        $updatedAdvert = $this->advertRepository->updateAdvert($advert);

        return new JsonResponse($updatedAdvert->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/adverts/{id}", name="delete_advertisement", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function delete($id): JsonResponse
    {
        $advert = $this->advertRepository->findOneBy(['id' => $id]);

        $this->advertRepository->removeAdvert($advert);

        return new JsonResponse(['status' => 'Advert deleted'], Response::HTTP_NO_CONTENT);
    }
}