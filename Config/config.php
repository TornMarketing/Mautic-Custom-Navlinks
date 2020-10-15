<?php

return [
    'name'        => 'NavLinks',
    'description' => 'Add custom navigation links',
    'author'      => 'Dwayne Taylor - Torn Marketing',
    'version'     => '1.0.0',

    'routes' => [
        'main' => [
            'mautic_navlinks_index' => [
                'path'       => '/navlinks/{page}',
                'controller' => 'CustomNavigationLinksBundle:NavLinks:index',
            ],
            'mautic_navlinks_custom' => [
                'path'       => '/navlinks/access',
                'controller' => 'CustomNavigationLinksBundle:NavLinks:customNavLinks',
            ],
            'mautic_navlinks_action' => [
                'path'       => '/navlinks/{objectAction}/{objectId}',
                'controller' => 'CustomNavigationLinksBundle:NavLinks:execute',
            ],
        ],
    ],
    'menu' => [
        'admin' => [
            'mautic.navlinks' => [
                'route'    => 'mautic_navlinks_index',
                'priority' => 100,
            ],
        ],
    ],
    
    'services' => [
        'integrations' => [
            'mautic.integration.navlinks' => [
                'class'     => \MauticPlugin\CustomNavigationLinksBundle\Integration\NavLinksIntegration::class,
                'arguments' => [
                    'event_dispatcher',
                    'mautic.helper.cache_storage',
                    'doctrine.orm.entity_manager',
                    'session',
                    'request_stack',
                    'router',
                    'translator',
                    'logger',
                    'mautic.helper.encryption',
                    'mautic.lead.model.lead',
                    'mautic.lead.model.company',
                    'mautic.helper.paths',
                    'mautic.core.model.notification',
                    'mautic.lead.model.field',
                    'mautic.plugin.model.integration_entity',
                    'mautic.lead.model.dnc',
                ],
            ],
        ],
        'forms' => [
            'mautic.form.type.navlinks' => [
                'class' => \MauticPlugin\CustomNavigationLinksBundle\Form\Type\NavLinkType::class,
            ],
        ],
        'events' => [
            'mautic.navlinks.subscriber.navlinks' => [
                'class'     => \MauticPlugin\CustomNavigationLinksBundle\EventListener\NavLinksSubscriber::class,
                'arguments' => [
                    'router',
                    'mautic.helper.ip_lookup',
                    'mautic.core.model.auditlog',
                    'mautic.page.model.trackable',
                    'mautic.page.helper.token',
                    'mautic.asset.helper.token',
                    'mautic.navlinks.model.navlinks',
                    'request_stack',
                ],
            ],
        ],
        'models' => [
            'mautic.navlinks.model.navlinks' => [
                'class'     => \MauticPlugin\CustomNavigationLinksBundle\Model\NavLinksModel::class,
                'arguments' => [
                    'mautic.form.model.form',
                    'mautic.page.model.trackable',
                    'mautic.helper.templating',
                    'event_dispatcher',
                    'mautic.lead.model.field',
                    'mautic.tracker.contact',
                    'doctrine.orm.entity_manager',
                ],
                'public' => true,
                'alias' => 'model.navlinks.navlinks'
            ],
        ],
    ],
];