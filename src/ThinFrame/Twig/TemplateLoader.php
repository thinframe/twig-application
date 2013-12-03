<?php

/**
 * /src/ThinFrame/Twig/TemplateLoader.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Twig;

/**
 * Class TemplateLoader
 *
 * @package ThinFrame\Twig
 * @since   0.1
 */
class TemplateLoader extends \Twig_Loader_Filesystem
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
     * {@inheritdoc}
     */
    public function exists($name)
    {
        try {
            $this->pathMapper->translate($name);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function findTemplate($name)
    {
        return $this->pathMapper->translate((string)$name);
    }
}
