<?php declare(strict_types=1);

namespace LivewireUI\Spotlight\Tests;

use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;

class SpotlightCommandDependenciesTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function it_is_empty_by_default(): void
    {
        $dependencies = new SpotlightCommandDependencies();

        self::assertEquals([], $dependencies->toArray());
    }

    /** @test */
    public function it_correctly_serializes_for_a_single_dependency(): void
    {
        $dependencies = SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('::id-1::')
                    ->setPlaceholder('::placeholder-1::')
            );

        self::assertEquals([
            [
                'id' => '::id-1::',
                'placeholder' => '::placeholder-1::',
                'type' => 'search',
            ]
        ], $dependencies->toArray());
    }

    /** @test */
    public function it_returns_the_dependencies_in_reversed_order(): void
    {
        $dependencies = SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('::id-1::')
                    ->setPlaceholder('::placeholder-1::')
            )
            ->add(
                SpotlightCommandDependency::make('::id-2::')
                    ->setPlaceholder('::placeholder-2::')
            );

        self::assertEquals([
            [
                'id' => '::id-2::',
                'placeholder' => '::placeholder-2::',
                'type' => 'search',
            ],
            [
                'id' => '::id-1::',
                'placeholder' => '::placeholder-1::',
                'type' => 'search',
            ],
        ], $dependencies->toArray());
    }
}
