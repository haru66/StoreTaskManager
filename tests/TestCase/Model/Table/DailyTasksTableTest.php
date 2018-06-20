<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DailytasksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DailytasksTable Test Case
 */
class DailytasksTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DailytasksTable
     */
    public $Dailytasks;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.dailytasks',
        'app.users',
        'app.stores'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Dailytasks') ? [] : ['className' => DailytasksTable::class];
        $this->Dailytasks = TableRegistry::getTableLocator()->get('Dailytasks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Dailytasks);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
