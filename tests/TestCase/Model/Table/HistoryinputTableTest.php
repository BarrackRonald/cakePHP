<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HistoryInputTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HistoryInputTable Test Case
 */
class HistoryInputTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\HistoryInputTable
     */
    protected $HistoryInput;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.HistoryInput',
        'app.Products',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('HistoryInput') ? [] : ['className' => HistoryInputTable::class];
        $this->HistoryInput = $this->getTableLocator()->get('HistoryInput', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->HistoryInput);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\HistoryInputTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\HistoryInputTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
