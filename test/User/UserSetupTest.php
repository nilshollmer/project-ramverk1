<?php

namespace Nihl\User;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Setup test for User model class
 */
class UserSetupTest extends TestCase
{
    /**
     * Assert that class object is created
     */
    public function testCreateUser()
    {
        $model = new User();
        $this->assertInstanceOf("Nihl\User\User", $model);
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
