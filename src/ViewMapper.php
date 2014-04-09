<?php

/**
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Twig;

use PhpCollection\Map;
use ThinFrame\Applications\DependencyInjection\ApplicationAwareTrait;
use ThinFrame\Foundation\Exception\InvalidArgumentException;

/**
 * Class ViewMapper
 * @package ThinFrame\Twig
 * @since   0.2
 */
class ViewMapper
{
    use ApplicationAwareTrait;

    /**
     * @var Map
     */
    private $paths;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->paths = new Map();
    }

    /**
     * Map applications views
     */
    public function mapViews()
    {
        foreach ($this->application->getMetadata() as $applicationName => $metadata) {
            /* @var $metadata Map */
            if ($metadata->containsKey('views')) {
                $this->addMapping(
                    $applicationName,
                    $metadata->get('path')->get() . DIRECTORY_SEPARATOR . $metadata->get('views')->get()
                );
            }
        }
    }


    /**
     * Add a path mapping
     *
     * @param string $name
     * @param string $path
     */
    public function addMapping($name, $path)
    {
        $this->paths->set($name, $path);
    }

    /**
     * Translate view path
     *
     * @param string $identifier
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public function translate($identifier)
    {
        $parts = explode(':', $identifier, 2);
        if (count($parts) == 2) {
            $basePath = $this->paths->get($parts[0])->getOrThrow(
                new InvalidArgumentException('Invalid application name for view identifier: ' . $identifier)
            );
            if (!file_exists($basePath . DIRECTORY_SEPARATOR . $parts[1])) {
                throw new InvalidArgumentException('Invalid view name');
            }

            return $basePath . DIRECTORY_SEPARATOR . $parts[1];
        } else {
            throw new InvalidArgumentException('Invalid view name provided');
        }
    }
}
