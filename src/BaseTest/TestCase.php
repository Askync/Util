<?php
namespace Askync\Utils\BaseTest;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        if(!is_file( __DIR__.'/../../../../../bootstrap/app.php' )) {
            throw new \Exception('Application path is incorrect');
        }
        return require __DIR__.'/../../../../../bootstrap/app.php';
    }
}
