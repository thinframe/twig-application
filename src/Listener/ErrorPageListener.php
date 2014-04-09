<?php

/**
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Twig\Listener;


use ThinFrame\Events\ListenerInterface;
use ThinFrame\Server\Event\HttpExceptionEvent;

/**
 * Class ErrorPageListener
 *
 * @package ThinFrame\Twig\Listeners
 * @since   0.2
 */
class ErrorPageListener implements ListenerInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twigEnvironment;

    /**
     * @var array
     */
    private $mappings = [];

    /**
     * @param \Twig_Environment $twigEnvironment
     */
    public function __construct(\Twig_Environment $twigEnvironment)
    {
        $this->twigEnvironment = $twigEnvironment;
    }

    /**
     * Set error code mappings
     *
     * @param array $mappings
     */
    public function setCodeMappings(array $mappings)
    {
        $this->mappings = $mappings;
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
        $statusCode = (string)$event->getHttpException()->getStatusCode();
        $view       = null;
        foreach ($this->mappings as $errorCodeMapping) {
            if ($errorCodeMapping['code'] == $statusCode) {
                $view = $errorCodeMapping['view'];

                break;
            }
        }
        if (is_null($view)) {
            return;
        }

        try {
            $event->getResponse()->setStatusCode($event->getHttpException()->getStatusCode());
            $event->getResponse()->setContent(
                $this->twigEnvironment->render(
                    $view,
                    ['exception' => $event->getHttpException()]
                )
            );
            $event->stopPropagation();
        } catch (\Exception $e) {

        }
    }
}