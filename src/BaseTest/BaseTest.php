<?php
namespace Askync\Utils\BaseTest;

use PHPUnit\Runner\BaseTestRunner;

class BaseTest extends TestCase {
    use TestUtils, InteractWithExceptionHandling;

    public function setUp() :void {
        parent::setUp();
        $this->printTestName($this->getName());
    }

    public function tearDown() :void {
        parent::tearDown();
        $this->printSuccessInfo( BaseTestRunner::STATUS_PASSED == $this->getStatus() ? true : false);
    }
}
