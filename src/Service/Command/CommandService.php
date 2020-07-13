<?php

namespace App\Service\Command;

use App\Entity\Advert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Advert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advert[]    findAll()
 * @method Advert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class CommandService extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advert::class);
    }

    // Function to list TOP 10 advertisements in Command (php bin/console ads-status)
    public function topAds()
    {
        $adverts = $this->findBy(array(), array('views' => 'DESC'));
        $data = [];
        $i = 1;
        foreach ($adverts as $advert) {
            $views = $advert->getViews();
            $label = $advert->getLabel();

            $element = $i . ' "' . $label . '" has ' . $views . ' views!';
            $data[] = $element;
            if (++$i == 11) break;
        }
        return $data;
    }


}