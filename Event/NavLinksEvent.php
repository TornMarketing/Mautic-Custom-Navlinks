<?php

/*
 * @copyright   2016 Mautic, Inc. All rights reserved
 * @author      Mautic, Inc
 *
 * @link        https://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\CustomNavigationLinksBundle\Event;

use Mautic\CoreBundle\Event\CommonEvent;
use MauticPlugin\CustomNavigationLinksBundle\Entity\NavLinks;

/**
 * Class NavLinksEvent.
 */
class NavLinksEvent extends CommonEvent
{
    /**
     * @param bool|false $isNew
     */
    public function __construct(NavLinks $navlinks, $isNew = false)
    {
        $this->entity = $navlinks;
        $this->isNew  = $isNew;
    }

    /**
     * Returns the NavLinks entity.
     *
     * @return NavLinksEvent
     */
    public function getNavLinks()
    {
        return $this->entity;
    }

    /**
     * Sets the NavLinks entity.
     */
    public function setNavLinks(NavLinks $navlinks)
    {
        $this->entity = $navlinks;
    }
}
