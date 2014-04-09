<?php

namespace ThinFrame\Twig\Listeners;


use ThinFrame\Events\ListenerInterface;
use ThinFrame\Server\Events\HttpExceptionEvent;
use ThinFrame\Twig\Configuration;

/**
 * Class ErrorPageListener
 *
 * @package ThinFrame\Twig\Listeners
 * @since   0.2
 */
class ErrorPageListener implements ListenerInterface
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @param Configuration     $configuration
     * @param \Twig_Environment $twig
     */
    public function __construct(Configuration $configuration, \Twig_Environment $twig)
    {
        $this->configuration = $configuration;
        $this->twig          = $twig;
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            HttpExceptionEvent::EVENT_ID => [
                'method' => 'onHttpException'
            ]
        ];
    }

    /**
     * Handle HTTP exceptions
     *
     * @param HttpExceptionEvent $event
     */
    public function onHttpException(HttpExceptionEvent $event)
    {
        $config     = $this->configuration->getConfiguration();
        $statusCode = (string)$event->getHttpException()->getStatusCode();
        if (isset($config['error_views']) && isset($config['error_views'][$statusCode])) {
            try {
                $event->getResponse()->setStatusCode($event->getHttpException()->getStatusCode());
                $event->getResponse()->setContent(
                    $this->twig->render(
                        $config['error_views'][$statusCode],
                        ['exception' => $event->getHttpException()]
                    )
                );
                $event->stopPropagation();
            } catch (\Exception $e) {

            }
        }
    }

}