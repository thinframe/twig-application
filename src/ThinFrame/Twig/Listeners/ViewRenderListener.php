<?php
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
                    $this->pathMapper->translate($response->getIdentifier()),
                    $response->getVariables()
                )
            );
        }
    }
}
