<?php

    $view->extend('MauticCoreBundle:Default:content.html.php');
    $view['slots']->set('mauticContent', 'navlinks');
    $view['slots']->set('headerTitle', $item->getName());

    $view['slots']->set(
        'actions',
        $view->render(
            'MauticCoreBundle:Helper:page_actions.html.php',
            [
                'item'            => $item,
                'templateButtons' => [
                    'edit' => $view['security']->hasEntityAccess(
                        $permissions['navlinks:items:editown'],
                        $permissions['navlinks:items:editother'],
                        $item->getCreatedBy()
                    ),
                    'clone'  => $permissions['navlinks:items:create'],
                    'delete' => $view['security']->hasEntityAccess(
                        $permissions['navlinks:items:deleteown'],
                        $permissions['navlinks:items:deleteother'],
                        $item->getCreatedBy()
                    ),
                    'close' => $view['security']->isGranted('navlinks:items:view'),
                ],
                'routeBase' => 'navlinks',
                'langVar'   => 'navlinks',
            ]
        )
    );

?>

<!-- start: box layout -->
<div class="box-layout">
    
    <!-- container -->
    <div class="col-md-9 bg-auto height-auto bdr-r pa-md">
        <div class="row">
            <div class="col-md-2">
                <b><?php echo $view['translator']->trans('plugin.customnavlinks.location'); ?>:</b>
            </div>
            <div class="col-md-6">
                <?php echo $item->getLocation();?>
            </div>
        </div> 

        <div class="row">
            <div class="col-md-2">
                <b><?php echo $view['translator']->trans('plugin.customnavlinks.label'); ?>:</b>
            </div>
            <div class="col-md-6">
                <?php echo $item->getName();?>
            </div>
        </div> 

        <div class="row">
            <div class="col-md-2">
            <b><?php echo $view['translator']->trans('plugin.customnavlinks.order'); ?>:</b>
            </div>
            <div class="col-md-6">
                <?php echo $item->getOrder();?>
            </div>
        </div> 

        <div class="row">
            <div class="col-md-2">
            <b><?php echo $view['translator']->trans('plugin.customnavlinks.icon'); ?>:</b>
            </div>
            <div class="col-md-6">
                <?php echo $item->getIcon();?>
            </div>
        </div> 

        <div class="row">
            <div class="col-md-2">
            <b><?php echo $view['translator']->trans('plugin.customnavlinks.url'); ?>:</b>
            </div>
            <div class="col-md-6">
                <?php echo $item->getUrl();?>
            </div>
        </div> 

        <div class="row">
            <div class="col-md-2">
            <b><?php echo $view['translator']->trans('plugin.customnavlinks.nav_type'); ?>:</b>
            </div>
            <div class="col-md-6">
                <?php echo $item->getNavType();?>
            </div>
        </div>          
    </div>
</div>