<?php declare(strict_types=1);

namespace LivewireUI\Spotlight\Tests;

use Generator;
use LivewireUI\Spotlight\SpotlightCommandDependency;

class SpotlightCommandDependencyTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @dataProvider dependencyProvider
     */
    public function it_can_be_turned_into_an_array(string $identifier, callable $configureDependency, array $expected): void
    {
        $commandDependency = SpotlightCommandDependency::make($identifier);

        $configureDependency($commandDependency);

        self::assertEquals($expected, $commandDependency->toArray());
    }

    public function dependencyProvider(): Generator
    {
        yield from [
            'no explicit type' => [
                '::id::',
                function (SpotlightCommandDependency $dependency) {
                    $dependency->setPlaceholder('::placeholder-1::');
                },
                [
                    'id' => '::id::',
                    'placeholder' => '::placeholder-1::',
                    'type' => SpotlightCommandDependency::SEARCH,
                ]
            ],

            'provide explicit type' => [
                '::id::',
                function (SpotlightCommandDependency $dependency) {
                    $dependency
                        ->setType(SpotlightCommandDependency::INPUT)
                        ->setPlaceholder('::placeholder-2::');
                },
                [
                    'id' => '::id::',
                    'placeholder' => '::placeholder-2::',
                    'type' => SpotlightCommandDependency::INPUT,
                ]
            ]
        ];
    }
}
