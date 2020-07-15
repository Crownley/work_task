<?php

namespace App\Repository;

use App\Entity\Advert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Advert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advert[]    findAll()
 * @method Advert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Advert::class);
        $this->manager = $manager;
    }

    // Advertisement's creation function
    public function saveAdvert($label, $content)
    {
        $newAdvert = new Advert();

        $newAdvert
            ->setLabel($label)
            ->setContent($content);


        $this->manager->persist($newAdvert);
        $this->manager->flush();
    }

    // Advertisement's update function
    public function updateAdvert(Advert $advert): Advert
    {
        $this->manager->persist($advert);
        $this->manager->flush();
    
        return $advert;
    }

    // Advertisement's views counter
    public function updateViews($advert)
    {
        $newViews = $advert->getViews() +1; 
        $advert->setViews($newViews);
        $this->manager->persist($advert);
        $this->manager->flush();

    }
    // Advertisement's remove function
    public function removeAdvert(Advert $advert)
    {
        $this->manager->remove($advert);
        $this->manager->flush();
    }

}