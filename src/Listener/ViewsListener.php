<?php

namespace ThinFrame\Twig\Listener;

use ThinFrame\Annotations\Collector;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Foundation\Exception\RuntimeException;
use ThinFrame\Karma\Event\ControllerResponseEvent;
use ThinFrame\Karma\Events;
use ThinFrame\Twig\View;
use ThinFrame\Twig\ViewMapper;

/**
 * Class ViewsListener
 *
 * @package ThinFrame\Twig\Listener
 */
class ViewsListener implements ListenerInterface
{
    /**
     * @var ViewMapper
     */
    private $viewMapper;

    /**
     * @var \Twig_Environment
     */
    private $twigEnvironment;

    /**
     * @var Collector
     */
    private $annotationsCollector;

    /**
     * Constructor
     *
     * @param ViewMapper        $viewMapper
     * @param \Twig_Environment $twigEnvironment
     * @param Collector         $collector
     */
    public function __construct(ViewMapper $viewMapper, \Twig_Environment $twigEnvironment, Collector $collector)
    {
        $this->viewMapper           = $viewMapper;
        $this->twigEnvironment      = $twigEnvironment;
        $this->annotationsCollector = $collector;
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            Events::PRE_SERVER_START          => ['method' => 'onServerPreStart'],
            ControllerResponseEvent::EVENT_ID => ['method' => 'onControllerResponse']
        ];
    }

    /**
     * Map view paths
     */
    public function onServerPreStart()
    {
        $this->viewMapper->mapViews();
    }

    /**
     * Handle twig views
     *
     * @param ControllerResponseEvent $event
     *
     * @throws RuntimeException
     */
    public function onControllerResponse(ControllerResponseEvent $event)
    {
        if (is_object($event->getActionResult()) && $event->getActionResult() instanceof View) {
            /** @var View $view */
            $view = $event->getActionResult();

            $view->setTwigEnvironment($this->twigEnvironment);

            return;
        }

        $annotations = $this->annotationsCollector->getMethodAnnotations(
            get_class($event->getController()),
            $event->getActionName()
        );

        if (isset($annotations['View'])) {
            if (count($annotations['View']) > 1) {
                throw new RuntimeException('Only one `View` annotation allowed per action');
            }
            $viewSettings = $annotations['View'][0];
            if ($viewSettings->type == 'Twig') {
                if (isset($viewSettings->template)) {
                    $view = new View($viewSettings->template, is_array(
                        $event->getActionResult()
                    ) ? $event->getActionResult() : [$event->getActionResult()]);
                    $view->setTwigEnvironment($this->twigEnvironment);
                    $event->getPayload()->set('actionResult', $view);
                } else {
                    throw new RuntimeException('Missing template property for Twig View annotations in ' . get_class(
                            $event->getController()
                        ) . ':' . $event->getActionName());
                }
            }
        }
    }
}
