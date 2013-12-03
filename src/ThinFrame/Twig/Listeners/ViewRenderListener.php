<?php

/**
 * /src/ThinFrame/Twig/Listeners/ViewRenderListener.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Twig\Listeners;

use ThinFrame\Events\ListenerInterface;
use ThinFrame\Karma\Events\ActionResponseEvent;
use ThinFrame\Twig\View;
use ThinFrame\Twig\ViewPathMapper;

/**
 * Class ViewRenderListener
 *
 * @package ThinFrame\Twig\Listeners
 * @since   0.1
 */
class ViewRenderListener implements ListenerInterface
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
     * Constructor
     *
     * @param \Twig_Environment $twig
     * @param ViewPathMapper    $pathMapper
     */
    public function __construct(\Twig_Environment $twig, ViewPathMapper $pathMapper)
    {
        $this->twig       = $twig;
        $this->pathMapper = $pathMapper;
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            ActionResponseEvent::EVENT_ID => [
                'method' => 'onActionResponse'
            ]
        ];
    }

    /**
     * Handle action response
     *
     * @param ActionResponseEvent $event
     */
    public function onActionResponse(ActionResponseEvent $event)
    {
        $response = $event->getActionResponse();
        if (is_object($response) && $response instanceof View) {
            /* @var $response View */
            $event->setActionResponse(
                $this->twig->render(
                    $response->getIdentifier(),
                    $response->getVariables()
                )
            );
        }
    }
}
