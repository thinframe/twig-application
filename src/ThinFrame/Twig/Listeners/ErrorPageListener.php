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
        print_r($config);
    }

}