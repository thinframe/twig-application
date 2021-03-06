<?php

namespace ThinFrame\Twig\Listeners;

use PhpCollection\Map;
use Stringy\StaticStringy;
use ThinFrame\Applications\DependencyInjection\ApplicationAwareTrait;
use ThinFrame\Events\ListenerInterface;

/**
 * Class CacheListener
 *
 * @package ThinFrame\Twig\Listeners
 * @since   0.2
 */
class CacheListener implements ListenerInterface
{
    use ApplicationAwareTrait;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * Constructor
     *
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            'karma.cache.clear'  => ['method' => 'onCacheClear'],
            'karma.cache.warmup' => ['method' => 'onCacheWarmup'],
        ];
    }

    /**
     * Delete all cached templates
     */
    public function onCacheClear()
    {
        if ($this->twig->getCache() and is_dir($this->twig->getCache())) {
            $this->deleteTwigFilesFrom($this->twig->getCache());
        }
    }

    /**
     * Caches all twig templates
     */
    public function onCacheWarmup()
    {
        foreach ($this->application->getMetadata() as $appName => $metadata) {
            /* @var $metadata Map */
            if ($metadata->containsKey('views')) {
                $viewsPath = $metadata->get('application_path')->get() . DIRECTORY_SEPARATOR . $metadata->get(
                        'views'
                    )->get();
                $this->cacheTwigFilesFrom($viewsPath, '', $appName);
            }
        }
    }

    /**
     * Caches all twig files
     *
     * @param        $dir
     * @param string $baseDir
     * @param        $applicationName
     */
    public function cacheTwigFilesFrom($dir, $baseDir = '', $applicationName)
    {
        foreach (scandir($dir) as $element) {
            if ($element == '.' || $element == '..') {
                continue;
            }
            if (is_dir($dir . DIRECTORY_SEPARATOR . $element)) {
                $this->cacheTwigFilesFrom(
                    $dir . DIRECTORY_SEPARATOR . $element,
                    $baseDir . $element . DIRECTORY_SEPARATOR,
                    $applicationName
                );
            } else {
                if (StaticStringy::endsWith($element, '.html.twig')) {
                    $this->twig->loadTemplate($applicationName . ":" . $baseDir . $element);
                }
            }
        }
    }

    /**
     * Remove directory
     *
     * @param $dir
     */
    public function deleteTwigFilesFrom($dir)
    {
        foreach (scandir($dir) as $element) {
            if ($element == '.' || $element == '..') {
                continue;
            }
            if (is_dir($dir . DIRECTORY_SEPARATOR . $element)) {
                $this->deleteTwigFilesFrom($dir . DIRECTORY_SEPARATOR . $element);
            } else {
                unlink($dir . DIRECTORY_SEPARATOR . $element);
            }
        }
        rmdir($dir);
    }
}
