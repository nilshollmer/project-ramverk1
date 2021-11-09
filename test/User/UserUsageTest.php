<?php

namespace Nihl\User;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Usage test for User model class
 */
class UserUsageTest extends TestCase
{
    // Create the di container and model.
    protected $di;
    protected $model;

    public function setUp()
    {
        // Create the di container and load services
        $this->di = new DIFactoryConfig();
        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $this->di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");
        $this->di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");
        
        // Set mock as service in the di replacing the original class
        $this->di->setShared("curl", "\Nihl\RemoteService\CurlWeatherReportMock");

        $this->model = $this->di->get("User");
        $this->model->setDI($this->di);
    }


    // /**
    //  * Test create an object of class through DI-injection
    //  */
    // public function testInjectUsingDI()
    // {
    //     // Create the di container and load services
    //     $di = new DIFactoryConfig();
    //     $di->loadServices(ANAX_INSTALL_PATH . "/config/di");
    //     $di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");

    //     $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

    //     $model = $di->get("weatherreport");
    //     $this->assertInstanceOf("Nihl\WeatherReport\WeatherReport", $model);
    // }
}
