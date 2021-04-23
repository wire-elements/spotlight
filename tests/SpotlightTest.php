<?php declare(strict_types=1);

namespace LivewireUI\Spotlight\Tests;

use TypeError;
use Generator;
use Livewire\Component;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightSearchResult;

class SpotlightTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Spotlight::$commands = [];
        $_SERVER['__command.called'] = 0;
    }

    /** @test */
    public function it_can_execute_a_registered_command(): void
    {
        $command = new TestCommand();
        Spotlight::registerCommand(get_class($command));

        (new Spotlight())->execute($command->getId());

        self::assertEquals(1, $_SERVER['__command.called']);
    }

    /**
     * @test
     * @dataProvider conditionalCommandRegistrationProvider
     */
    public function it_can_conditionally_register_commands(callable $registerCommand, int $expected): void
    {
        $command = new TestCommand();
        $registerCommand($command);

        try {
            (new Spotlight())->execute($command->getId());
        } catch (TypeError) {
            // Trying to execute a command that hasn't been registered
            // currently throws an exception. That's not what we want
            // to check for, however, so we'll ignore it for now.
        }

        self::assertEquals($expected, $_SERVER['__command.called']);
    }

    public function conditionalCommandRegistrationProvider(): Generator
    {
        yield from [
            'registerCommandIf (true)' => [
                function ($command) {
                    Spotlight::registerCommandIf(true, get_class($command));
                },
                1
            ],

            'registerCommandIf (false)' => [
                function ($command) {
                    Spotlight::registerCommandIf(false, get_class($command));
                },
                0
            ],

            'registerCommandUnless (true)' => [
                function ($command) {
                    Spotlight::registerCommandUnless(true, get_class($command));
                },
                0
            ],

            'registerCommandUnless (false)' => [
                function ($command) {
                    Spotlight::registerCommandUnless(false, get_class($command));
                },
                1
            ]
        ];
    }

    /**
     * @test
     * @dataProvider searchDependencyProvider
     */
    public function it_resolves_a_commands_dependencies_via_the_corresponding_method(string $dependency, array $expected): void
    {
        $command = new TestCommand();
        Spotlight::registerCommand(get_class($command));
        $spotlight = new Spotlight();

        $spotlight->searchDependency($command->getId(), $dependency, '::query::');

        self::assertEquals($spotlight->dependencyQueryResults, $expected);
    }

    public function searchDependencyProvider(): Generator
    {
        yield from [
            'searchFoo' => [
                'foo',
                [
                    [
                        'id' => '::foo-id::',
                        'name' => '::foo-name::',
                        'description' => '::foo-description::',
                    ]
                ]
            ],

            'searchBar' => [
                'bar',
                [
                    [
                        'id' => '::bar-id::',
                        'name' => '::bar-name::',
                        'description' => '::bar-description::',
                    ],
                ],
            ],

            'no method exists' => [
                'baz',
                [],
            ]
        ];
    }
}

class TestCommand extends SpotlightCommand
{
    public function execute()
    {
        ++$_SERVER['__command.called'];
    }

    public function searchFoo()
    {
        return collect([new SpotlightSearchResult(
            '::foo-id::',
            '::foo-name::',
            '::foo-description::',
        )]);
    }

    public function searchBar()
    {
        return collect([new SpotlightSearchResult(
            '::bar-id::',
            '::bar-name::',
            '::bar-description::',
        )]);
    }
}
