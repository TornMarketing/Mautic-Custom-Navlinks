<?php

namespace MauticPlugin\CustomNavigationLinksBundle\Controller;

use Mautic\CoreBundle\Controller\AbstractStandardFormController;
use Mautic\CoreBundle\Form\Type\DateRangeType;
use MauticPlugin\MauticFocusBundle\Entity\Focus;
use MauticPlugin\CustomNavigationLinksBundle\Model\NavLinksModel;
use MauticPlugin\CustomNavigationLinksBundle\Entity\NavLinks;
use MauticPlugin\CustomNavigationLinksBundle\Form\Type\NavLinkType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NavLinksController.
 */
class NavLinksController extends AbstractStandardFormController
{
    /**
     * @return string
     */
    protected function getControllerBase()
    {
        return 'CustomNavigationLinksBundle:NavLinks';
    }

    /**
     * @return string
     */
    protected function getModelName()
    {
        return 'navlinks';
    }

    /**
     * @param int $page
     *
     * @return JsonResponse|RedirectResponse|Response
     */
    public function indexAction($page = 1)
    {
        return parent::indexStandard($page);
    }

    /**
     * Generates new form and processes post data.
     *
     * @return JsonResponse|Response
     */
    public function newAction()
    {
        return parent::newStandard();
    }

    /**
     * Generates edit form and processes post data.
     *
     * @param int  $objectId
     * @param bool $ignorePost
     *
     * @return JsonResponse|Response
     */
    public function editAction($objectId, $ignorePost = false)
    {
        return parent::editStandard($objectId, $ignorePost);
    }    

    /**
     * Displays details on a navlinks.
     *
     * @param $objectId
     *
     * @return array|JsonResponse|RedirectResponse|Response
     */
    public function viewAction($objectId)
    {
        return parent::viewStandard($objectId, 'navlinks', 'plugin.navlinks');
    }

    /**
     * Clone an entity.
     *
     * @param int $objectId
     *
     * @return JsonResponse|RedirectResponse|Response
     */
    public function cloneAction($objectId)
    {
        return parent::cloneStandard($objectId);
    }

    /**
     * Deletes the entity.
     *
     * @param int $objectId
     *
     * @return JsonResponse|RedirectResponse
     */
    public function deleteAction($objectId)
    {
        return parent::deleteStandard($objectId);
    }

    /**
     * Deletes a group of entities.
     *
     * @return JsonResponse|RedirectResponse
     */
    public function batchDeleteAction()
    {
        return parent::batchDeleteStandard();
    }

    public function customNavLinksAction()
    {
        $url = $this->request->get('url');
        //print $url; exit;
        return $this->delegateView(
            [
                'viewParameters' => [
                    'url' => $url,
                ],
                'contentTemplate' => 'CustomNavigationLinksBundle:NavLinks:customNavLinks.html.php',
                'passthroughVars' => [
                    'activeLink' => 'mautic_navlinks_index'
                ],

            ]
        );
    }

    /**
     * @param $action
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getViewArguments(array $args, $action)
    {
        if ('view' == $action) {
            /** @var NavLinks $item */
            $item = $args['viewParameters']['item'];

            // For line graphs in the view
            $dateRangeValues = $this->request->get('daterange', []);
            $dateRangeForm   = $this->get('form.factory')->create(
                DateRangeType::class,
                $dateRangeValues,
                [
                    'action' => $this->generateUrl(
                        'mautic_focus_action',
                        [
                            'objectAction' => 'view',
                            'objectId'     => $item->getId(),
                        ]
                    ),
                ]
            );

            /** @var NavLinksModel $model */
            $model = $this->getModel('navlinks');
            $args['viewParameters']['dateRangeForm'] = $dateRangeForm->createView();

        }

        return $args;
    }

    /**
     * @param $action
     *
     * @return array
     */
    protected function getPostActionRedirectArguments(array $args, $action)
    {
        $navlinks        = $this->request->request->get('navlinks', []);
        $updateSelect = 'POST' === $this->request->getMethod()
            ? ($navlinks['updateSelect'] ?? false)
            : $this->request->get('updateSelect', false);

        if ($updateSelect) {
            switch ($action) {
                case 'new':
                case 'edit':
                    $passthrough = $args['passthroughVars'];
                    $passthrough = array_merge(
                        $passthrough,
                        [
                            'updateSelect' => $updateSelect,
                            'id'           => $args['entity']->getId(),
                            'name'         => $args['entity']->getName(),
                        ]
                    );
                    $args['passthroughVars'] = $passthrough;
                    break;
            }
        }

        return $args;
    }

    /**
     * @return array
     */
    protected function getEntityFormOptions()
    {
        $navlinks        = $this->request->request->get('navlinks', []);
        $updateSelect = 'POST' === $this->request->getMethod()
            ? ($focus['updateSelect'] ?? false)
            : $this->request->get('updateSelect', false);

        if ($updateSelect) {
            return ['update_select' => $updateSelect];
        }
    }

    /**
     * Return array of options update select response.
     *
     * @param string $updateSelect HTML id of the select
     * @param object $entity
     * @param string $nameMethod   name of the entity method holding the name
     * @param string $groupMethod  name of the entity method holding the select group
     *
     * @return array
     */
    protected function getUpdateSelectParams($updateSelect, $entity, $nameMethod = 'getName', $groupMethod = 'getLanguage')
    {
        return [
            'updateSelect' => $updateSelect,
            'id'           => $entity->getId(),
            'name'         => $entity->$nameMethod(),
        ];
    }
}
