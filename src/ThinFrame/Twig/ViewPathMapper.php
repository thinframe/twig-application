<?php

namespace ThinFrame\Twig;

use PhpCollection\Map;
use ThinFrame\Foundation\Exceptions\InvalidArgumentException;

/**
 * Class ViewPathMapper
 *
 * @package ThinFrame\Twig
 * @since   0.1
 */
class ViewPathMapper
{
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
                new InvalidArgumentException('Invalid application name for view')
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