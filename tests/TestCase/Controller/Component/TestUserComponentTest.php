<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\TestUserComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\TestUserComponent Test Case
 */
class TestUserComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Controller\Component\TestUserComponent
     */
    protected $TestUser;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->TestUser = new TestUserComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->TestUser);

        parent::tearDown();
    }
}
