<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\Mail2Component;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\Mail2Component Test Case
 */
class Mail2ComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Controller\Component\Mail2Component
     */
    protected $Mail2;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Mail2 = new Mail2Component($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Mail2);

        parent::tearDown();
    }
}
