<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\CRUDComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\CRUDComponent Test Case
 */
class CRUDComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Controller\Component\CRUDComponent
     */
    protected $CRUD;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->CRUD = new CRUDComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->CRUD);

        parent::tearDown();
    }
}
