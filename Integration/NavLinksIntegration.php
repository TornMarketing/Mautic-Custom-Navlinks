<?php

namespace MauticPlugin\CustomNavigationLinksBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractIntegration;

class NavLinksIntegration extends AbstractIntegration
{
    public function getName()
    {
        return 'NavLinks';
    }

    /**
     * Return's authentication method such as oauth2, oauth1a, key, etc.
     *
     * @return string
     */
    public function getAuthenticationType()
    {
        // Just use none for now and I'll build in "basic" later
        return 'none';
    }
}
