<?php

/**
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Twig\Listener;

use PhpCollection\Map;
use Stringy\StaticStringy;
use ThinFrame\Applications\DependencyInjection\ApplicationAwareTrait;
use ThinFrame\Events\DispatcherAwareTrait;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Events\SimpleEvent;
use ThinFrame\Karma\Events;

/**
 * Class CacheListener
 * @package ThinFrame\Twig\Listener
 * @since   0.2
 */
class CacheListener implements ListenerInterface
{
    use ApplicationAwareTrait;
    use DispatcherAwareTrait;

    /**
     * @var \Twig_Environment
     */
    private $twigEnvironment;

    /**
     * Constructor
     *
     * @param \Twig_Environment $twigEnvironment
     */
    public function __construct(\Twig_Environment $twigEnvironment)
    {
        $this->twigEnvironment = $twigEnvironment;
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            Events::CACHE_CLEAR  => ['method' => 'onCacheClear'],
            Events::CACHE_WARMUP => ['method' => 'onCacheWarmup'],
        ];
    }

    /**
     * Delete all cached templates
     */
    public function onCacheClear()
    {
        if ($this->twigEnvironment->getCache() and is_dir($this->twigEnvironment->getCache())) {
            $this->deleteTwigFilesFrom($this->twigEnvironment->getCache());
        }
    }

    /**
     * Caches all twig templates
     */
    public function onCacheWarmup()
    {
        $this->dispatcher->trigger(new SimpleEvent(Events::VIEWS_MAP));
        foreach ($this->application->getMetadata() as $appName => $metadata) {
            /* @var $metadata Map */
            if ($metadata->containsKey('views')) {
                $viewsPath = $metadata->get('path')->get() . DIRECTORY_SEPARATOR . $metadata->get(
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
                    $this->twigEnvironment->loadTemplate($applicationName . ":" . $baseDir . $element);
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
