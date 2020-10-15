<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\CustomNavigationLinksBundle;

use Doctrine\ORM\EntityManager;
use Mautic\CoreBundle\Factory\MauticFactory;
use Mautic\PluginBundle\Bundle\PluginBundleBase;
use Mautic\PluginBundle\Entity\Plugin;

/**
 * Class CustomNavigationLinksBundle.
 */
class CustomNavigationLinksBundle extends PluginBundleBase
{
    public static function onPluginInstall(Plugin $plugin, MauticFactory $factory, $metadata = null, $installedSchema = null)
    {
        if (null === $metadata) {
            $metadata = self::getMetadata($factory->getEntityManager());
        }

        if (null !== $metadata) {
            parent::onPluginInstall($plugin, $factory, $metadata, $installedSchema);
        }
    }

    /**
     * Fix: plugin installer doesn't find metadata entities for the plugin
     * PluginBundle/Controller/PluginController:410.
     *
     * @return array|null
     */
    private static function getMetadata(EntityManager $em)
    {
        $allMetadata   = $em->getMetadataFactory()->getAllMetadata();
        $currentSchema = $em->getConnection()->getSchemaManager()->createSchema();

        $classes = [];

        /** @var \Doctrine\ORM\Mapping\ClassMetadata $meta */
        foreach ($allMetadata as $meta) {
            if (false === strpos($meta->namespace, 'MauticPlugin\\CustomNavigationLinksBundle')) {
                continue;
            }

            $table = $meta->getTableName();

            if ($currentSchema->hasTable($table)) {
                continue;
            }

            $classes[] = $meta;
        }

        return $classes ?: null;
    }
}
