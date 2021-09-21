<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\CommonComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\CommonComponent Test Case
 */
class CommonComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Controller\Component\CommonComponent
     */
    protected $Common;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Common = new CommonComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Common);

        parent::tearDown();
    }
}
