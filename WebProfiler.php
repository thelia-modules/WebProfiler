<?php

/*
 * This file is part of the Thelia package.
 * http://www.thelia.net
 *
 * (c) OpenStudio <info@thelia.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebProfiler;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use Thelia\Module\BaseModule;
use TheliaSmarty\Template\DataCollectorSmartyParser;
use WebProfiler\DataCollector\SmartyDataCollector;

class WebProfiler extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'webprofiler';

    /*
     * You may now override BaseModuleInterface methods, such as:
     * install, destroy, preActivation, postActivation, preDeactivation, postDeactivation
     *
     * Have fun !
     */

    /**
     * Defines how services are loaded in your modules.
     */
    public static function configureServices(ServicesConfigurator $servicesConfigurator): void
    {
        $servicesConfigurator->load(self::getModuleCode().'\\', __DIR__)
            ->exclude([THELIA_MODULE_DIR.ucfirst(self::getModuleCode()).'/I18n/*'])
            ->exclude([THELIA_MODULE_DIR.ucfirst(self::getModuleCode()).'/DataCollector/SmartyDataCollector'])
            ->autowire(true)
            ->autoconfigure(true);

        $servicesConfigurator->set('data_collector.smarty', SmartyDataCollector::class)
            ->args([
                service(DataCollectorSmartyParser::class)->ignoreOnInvalid(),
            ])
            ->tag(
                'data_collector',
                [
                    'template' => '@WebProfilerModule/debug/dataCollector/smarty.html.twig',
                    'id' => 'smarty',
                    'priority' => 42,
                ]
            );
    }
}
