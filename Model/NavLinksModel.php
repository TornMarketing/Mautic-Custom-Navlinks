<?php

/*
 * @copyright   2016 Mautic, Inc. All rights reserved
 * @author      Mautic, Inc
 *
 * @link        https://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\CustomNavigationLinksBundle\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManager;
use Mautic\CoreBundle\Event\TokenReplacementEvent;
use Mautic\CoreBundle\Helper\Chart\ChartQuery;
use Mautic\CoreBundle\Helper\Chart\LineChart;
use Mautic\CoreBundle\Helper\TemplatingHelper;
use Mautic\CoreBundle\Model\FormModel;
use Mautic\LeadBundle\Entity\Lead;
use Mautic\LeadBundle\Model\FieldModel;
use Mautic\LeadBundle\Tracker\ContactTracker;
use Mautic\PageBundle\Model\TrackableModel;
use MauticPlugin\CustomNavigationLinksBundle\Entity\NavLinks;
use MauticPlugin\CustomNavigationLinksBundle\Event\NavLinksEvent;
use MauticPlugin\CustomNavigationLinksBundle\Form\Type\NavLinkType;
use Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class NavLinksModel extends FormModel
{
    /**
     * @var ContainerAwareEventDispatcher
     */
    protected $dispatcher;

    /**
     * @var \Mautic\FormBundle\Model\FormModel
     */
    protected $formModel;

    /**
     * @var TrackableModel
     */
    protected $trackableModel;

    /**
     * @var TemplatingHelper
     */
    protected $templating;

    /**
     * @var FieldModel
     */
    protected $leadFieldModel;

    /**
     * @var ContactTracker
     */
    protected $contactTracker;

    /**
     * 
     * @var EntityManager $entityManager
     */
    private static $entityManager;
    /**
     * NavLinksModel constructor.
     */
    public function __construct(
        \Mautic\FormBundle\Model\FormModel $formModel,
        TrackableModel $trackableModel,
        TemplatingHelper $templating,
        EventDispatcherInterface $dispatcher,
        FieldModel $leadFieldModel,
        ContactTracker $contactTracker,
        EntityManager $entityManager
    ) {
        $this->formModel      = $formModel;
        $this->trackableModel = $trackableModel;
        $this->templating     = $templating;
        $this->dispatcher     = $dispatcher;
        $this->leadFieldModel = $leadFieldModel;
        $this->contactTracker = $contactTracker;
        static::$entityManager = $entityManager;
    }

    /**
     * @return string
     */
    public function getActionRouteBase()
    {
        return 'navlinks';
    }

    /**
     * @return string
     */
    public function getPermissionBase()
    {
        return 'navlinks:items';
    }

    /**
     * {@inheritdoc}
     *
     * @param object                              $entity
     * @param \Symfony\Component\Form\FormFactory $formFactory
     * @param null                                $action
     * @param array                               $options
     *
     * @throws NotFoundHttpException
     */
    public function createForm($entity, $formFactory, $action = null, $options = [])
    {
        if (!$entity instanceof NavLinks) {
            throw new MethodNotAllowedHttpException(['NavLinks']);
        }

        if (!empty($action)) {
            $options['action'] = $action;
        }

        return $formFactory->create(NavLinkType::class, $entity, $options);
    }

    /**
     * {@inheritdoc}
     *
     * @return \MauticPlugin\CustomNavigationLinksBundle\Entity\NavLinksRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository(NavLinks::class);
    }

    /**
     * {@inheritdoc}
     *
     * @param null $id
     *
     * @return NavLinks
     */
    public function getEntity($id = null)
    {
        if (null === $id) {
            return new NavLinks();
        }

        return parent::getEntity($id);
    }

    /**
     * {@inheritdoc}
     *
     * @param NavLinks      $entity
     * @param bool|false $unlock
     */
    public function saveEntity($entity, $unlock = true)
    {
        parent::saveEntity($entity, $unlock);
        $this->getRepository()->saveEntity($entity);
    }

    
    /**
     * Get whether the color is light or dark.
     *
     * @param $hex
     * @param $level
     *
     * @return bool
     */
    public static function isLightColor($hex, $level = 200)
    {
        $hex = str_replace('#', '', $hex);
        $r   = hexdec(substr($hex, 0, 2));
        $g   = hexdec(substr($hex, 2, 2));
        $b   = hexdec(substr($hex, 4, 2));

        $compareWith = ((($r * 299) + ($g * 587) + ($b * 114)) / 1000);

        return $compareWith >= $level;
    }

    /**
     * {@inheritdoc}
     *
     * @return bool|NavLinksEvent|void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     */
    protected function dispatchEvent($action, &$entity, $isNew = false, Event $event = null)
    {
        if (!$entity instanceof NavLinks) {
            throw new MethodNotAllowedHttpException(['NavLinks']);
        }

        switch ($action) {
            case 'pre_save':
                $name = 'mautic.navlinks_pre_save';
                break;
            case 'post_save':
                $name = 'mautic.navlinks_post_save';
                break;
            case 'pre_delete':
                $name = 'mautic.navlinks_pre_delete';
                break;
            case 'post_delete':
                $name = 'mautic.navlinks_post_delete';
                break;
            default:
                return null;
        }

        if ($this->dispatcher->hasListeners($name)) {
            if (empty($event)) {
                $event = new NavLinksEvent($entity, $isNew);
                $event->setEntityManager($this->em);
            }

            $this->dispatcher->dispatch($name, $event);

            return $event;
        } else {
            return null;
        }
    }

    /**
     * Generate custom nav link from DB 
     */
    public function getCustomNavLinks()
    {
        //$navLinksRepo = $this->getRepository();
        // Fetch all the publish ustom nav links.
        $menus = $this->getRepository()->getCustomNavLinksByPublished();
        $menuArras = [];

        foreach($menus as $menu)
        {
            $menuRow = [];
            
            $menuRow['routeParameters'] = ['url' => $menu->getUrl()];
            $menuRow['iconClass'] = $menu->getIcon();
            $menuRow['priority'] = $menu->getOrder();
            if($menu->getNavType() == 'blank'){
                $menuRow['linkAttributes'] = ['target' => '_blank'];
                $menuRow['uri'] =  $menu->getUrl();
            }else{
                $menuRow['route'] = 'mautic_navlinks_custom';
            }

            //$menuArras['menu'][$menu->getLocation()][str_replace(' ', '.', $menu->getName())] = $menuRow;
            $menuArras['menu'][$menu->getLocation()][trim($menu->getName())] = $menuRow;
        }


        return $menuArras;
    }

    // Get path of the config.php file.
    public function getConfiArray()
    {
        return include dirname(__DIR__).'/Config/config.php';
    }

    // Update menu config of config.php from DB.
    public function updateMenuConfig()
    {
        // Base menu what will be here for custom nav links
        $baseMenu = ['menu' => [
            'admin' => [
                'mautic.navlinks' => [
                    'route'    => 'mautic_navlinks_index',
                    'priority' => 100,
                ],
            ],
        ]];

        $configFile = dirname(__DIR__).'/Config/config.php';
        $config = $this->getConfiArray();
        $menus = $this->getCustomNavLinks();
        $menus = array_merge_recursive($menus, $baseMenu);        
        $config = array_merge($config, $menus);
        
        // Update config.php file.
        file_put_contents($configFile, "<?php  \nreturn ".var_export($config, true)." ;");
    }

}
