<?php

/**
 * /src/ThinFrame/Twig/TemplateLoader.php
 *
 * @author Sorin Badea <sorin.badea91@gmail.com>
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
     * @var ViewMapper
     */
    private $pathMapper;

    /**
     * Constructor
     *
     * @param ViewMapper $pathMapper
     */
    public function __construct(ViewMapper $pathMapper)
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
