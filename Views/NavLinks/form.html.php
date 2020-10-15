<?php
$view->extend('MauticCoreBundle:Default:content.html.php');


$view['slots']->set('mauticContent', 'navlinks');

$header = ($entity->getId())
    ?
    $view['translator']->trans(
        'mautic.navlinks.edit',
        ['%name%' => $view['translator']->trans($entity->getName())]
    )
    :
    $view['translator']->trans('mautic.navlinks.new');
$view['slots']->set('headerTitle', $header);

//$attr = $form->vars['attr'];
echo $view['form']->start($form);
?>

<!-- start: box layout -->
<div class="box-layout">
     <!-- container -->
     <div class="col-md-9 bg-auto height-auto bdr-r pa-md">
     
            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($form['location']); ?>
                </div>
                <div class="col-md-6">
                    <?php echo $view['form']->row($form['order']); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($form['name']); ?>
                </div>
                <div class="col-md-6">
                    <?php echo $view['form']->row($form['icon']); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($form['url']); ?>
                </div>
                <div class="col-md-6">
                    <?php echo $view['form']->row($form['navType']); ?>
                </div>
            </div>
     </div>     
</div>
<div class="modal-form-buttons" style="margin-left: 15px;">
    
</div>
<?php echo $view['form']->end($form); ?>