<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\Logger\Handler;

use Gelf\PublisherInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Monolog\Handler\GelfHandler;
use Opengento\Logger\Traits\VerifyConfiguration;

/**
 * This wrapper is useful to add the "PublisherInterface" type in the constructor
 * and allow the DI to generate this handler.
 *
 * @package Opengento\Logger\Handler
 */
class GelfHandlerWrapper extends GelfHandler
{
    use VerifyConfiguration;

    /**
     * @var string
     */
    private $isEnabled;

    /**
     * @var string
     */
    private $levelPath;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var GelfHandler
     */
    private $gelfHandler;

    /**
     * GelfHandlerWrapper constructor.
     * @param PublisherInterface $publisher
     * @param ScopeConfigInterface $scopeConfig
     * @param string $isEnabled
     * @param string $levelPath
     */
    public function __construct(
        PublisherInterface $publisher,
        ScopeConfigInterface $scopeConfig,
        string $isEnabled,
        string $levelPath
    )
    {
        $this->publisher = $publisher;
        $this->isEnabled = $isEnabled;
        $this->levelPath = $levelPath;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return GelfHandler
     */
    final public function getInstance(): GelfHandler
    {
        if (!$this->gelfHandler) {
            $this->gelfHandler = new GelfHandler(
                $this->publisher,
                $this->scopeConfig->getValue($this->levelPath)
            );
        }

        return $this->gelfHandler;
    }
}
