<?php

namespace MauticPlugin\CustomNavigationLinksBundle\Entity;

use Mautic\CoreBundle\Entity\CommonRepository;

/**
 * WorldRepository
 */
class NavLinksRepository extends CommonRepository
{
    public function getCustomNavLinksByPublished($isPublished = true)
    {
        $q = $this->createQueryBuilder('f');
        $q->select('cvl')
        ->from(NavLinks::class, 'cvl')
        ->where('cvl.isPublished = :isPublished')
        ->setParameters(['isPublished' => $isPublished])
        ;
        return $q->getQuery()->getResult();
    }
}