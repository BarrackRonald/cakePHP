<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\DataComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\DataComponent Test Case
 */
class DataComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Controller\Component\DataComponent
     */
    protected $Data;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Data = new DataComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Data);

        parent::tearDown();
    }
}
