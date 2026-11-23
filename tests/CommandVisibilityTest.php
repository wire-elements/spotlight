<?php

namespace LivewireUI\Spotlight\Tests;

use Livewire\Livewire;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\Tests\Fixtures\HiddenCommand;
use LivewireUI\Spotlight\Tests\Fixtures\VisibleCommand;

class CommandVisibilityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Spotlight::$commands = [];
        Spotlight::registerCommand(VisibleCommand::class);
        Spotlight::registerCommand(HiddenCommand::class);

        VisibleCommand::$executed = false;
        VisibleCommand::$searched = false;
        HiddenCommand::$executed = false;
        HiddenCommand::$searched = false;
    }

    public function test_render_respects_should_be_shown(): void
    {
        $commands = Livewire::test(Spotlight::class)->viewData('commands');

        $this->assertSame(
            [(new VisibleCommand())->getId()],
            collect($commands)->pluck('id')->all()
        );
    }

    public function test_execute_runs_command(): void
    {
        Livewire::test(Spotlight::class)
            ->call('execute', (new VisibleCommand())->getId());

        $this->assertTrue(VisibleCommand::$executed);
    }

    public function test_execute_respects_should_be_shown(): void
    {
        Livewire::test(Spotlight::class)
            ->call('execute', (new HiddenCommand())->getId());

        $this->assertFalse(HiddenCommand::$executed);
    }

    public function test_search_dependency_returns_results(): void
    {
        $component = Livewire::test(Spotlight::class)
            ->call('searchDependency', (new VisibleCommand())->getId(), 'team', 'Te');

        $this->assertTrue(VisibleCommand::$searched);
        $this->assertCount(1, $component->get('dependencyQueryResults'));
    }

    public function test_search_dependency_respects_should_be_shown(): void
    {
        $component = Livewire::test(Spotlight::class)
            ->call('searchDependency', (new HiddenCommand())->getId(), 'team', 'Te');

        $this->assertFalse(HiddenCommand::$searched);
        $this->assertSame([], $component->get('dependencyQueryResults'));
    }

    public function test_unknown_command_id_is_ignored(): void
    {
        Livewire::test(Spotlight::class)
            ->call('execute', 'does-not-exist')
            ->assertOk();

        $this->assertFalse(VisibleCommand::$executed);
        $this->assertFalse(HiddenCommand::$executed);
    }
}
