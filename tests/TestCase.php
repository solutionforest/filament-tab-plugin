<?php

namespace SolutionForest\GridLayoutPlugin\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use SolutionForest\TabLayoutPlugin\TabLayoutPluginServiceProvider;


class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            TabLayoutPluginServiceProvider::class
        ];
    }

    public function getEnvironmentSetUp($app)
    {
    }
}
