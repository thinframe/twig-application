<?php

/**
 * /src/ThinFrame/Twig/Listeners/ViewRenderListener.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Twig\Listeners;

use ThinFrame\Annotations\Collector;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Karma\Events\ControllerResponseEvent;
use ThinFrame\Twig\TwigView;
use ThinFrame\Twig\ViewPathMapper;

/**
 * Class ViewRenderListener
 *
 * @package ThinFrame\Twig\Listeners
 * @since   0.1
 */
class ViewListener implements ListenerInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var ViewPathMapper
     */
    private $pathMapper;

    /**
     * @var Collector
     */
    private $annotationCollector;

    /**
     * Constructor
     *
     * @param \Twig_Environment $twig
     * @param ViewPathMapper    $pathMapper
     * @param Collector         $collector
     */
    public function __construct(\Twig_Environment $twig, ViewPathMapper $pathMapper, Collector $collector)
    {
        $this->twig                = $twig;
        $this->pathMapper          = $pathMapper;
        $this->annotationCollector = $collector;
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            ControllerResponseEvent::EVENT_ID => [
                'method' => 'onActionResponse'
            ]
        ];
    }

    /**
     * Handle action response
     *
     * @param ControllerResponseEvent $event
     */
    public function onActionResponse(ControllerResponseEvent $event)
    {
        if ($event->getActionResponse() instanceof TwigView) {
            $event->getActionResponse()->setTwig($this->twig);
            return;
        }
        $annotations = $this->annotationCollector->getMethodAnnotations(
            get_class($event->getController()),
            $event->getActionName()
        );
        if (isset($annotations['View']) && $annotations['View'][0] = 'Twig') {
            if (!isset($annotations['Template'])) {
                return;
            }
            $view = new TwigView($annotations['Template'][0], $event->getActionResponse());
            $view->setTwig($this->twig);
            $event->setActionResponse($view);
        }
    }
}
