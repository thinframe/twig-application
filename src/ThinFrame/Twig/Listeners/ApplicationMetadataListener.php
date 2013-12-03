<?php

/**
 * /src/ThinFrame/Twig/Listeners/ApplicationMetadataListener.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Twig\Listeners;

use ThinFrame\Events\ListenerInterface;
use ThinFrame\Events\SimpleEvent;
use ThinFrame\Karma\KarmaApplication;
use ThinFrame\Twig\ViewPathMapper;

/**
 * Class ApplicationMetadataListener
 *
 * @package ThinFrame\Twig\Listeners
 * @since   0.1
 */
class ApplicationMetadataListener implements ListenerInterface
{
    /**
     * @var ViewPathMapper
     */
    private $pathMapper;

    /**
     * Constructor
     *
     * @param ViewPathMapper $pathMapper
     */
    public function __construct(ViewPathMapper $pathMapper)
    {
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
            KarmaApplication::APPLICATION_METADATA_EVENT_ID => [
                'method' => 'onApplicationMetadata'
            ]
        ];
    }

    /**
     * Handle application metadata
     *
     * @param SimpleEvent $event
     */
    public function onApplicationMetadata(SimpleEvent $event)
    {
        $metadata = $event->getPayload()->get('metadata')->get();
        /* @var $metadata \PhpCollection\Map */
        if ($metadata->get('views_path')->isDefined()) {
            $this->pathMapper->addMapping(
                $metadata->get('application_name')->get(),
                $metadata->get('application_path')->get() . DIRECTORY_SEPARATOR . $metadata->get('views_path')->get()
            );
        }
    }
}
