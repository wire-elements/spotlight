<?php declare(strict_types=1);

namespace LivewireUI\Spotlight\Tests;

use LivewireUI\Spotlight\SpotlightSearchResult;

class SpotlightSearchResultTest extends \PHPUnit\Framework\TestCase
{
    private SpotlightSearchResult $result;

    protected function setUp(): void
    {
        $this->result = new SpotlightSearchResult('::id::', '::name::', '::description::');
    }

    /** @test */
    public function it_can_be_turned_into_an_array(): void
    {
        self::assertEquals([
            'id' => '::id::',
            'name' => '::name::',
            'description' => '::description::',
        ], $this->result->toArray());
    }

    /** @test */
    public function it_returns_its_id(): void
    {
        self::assertEquals('::id::', $this->result->getId());
    }

    /** @test */
    public function it_returns_its_description(): void
    {
        self::assertEquals('::description::', $this->result->getDescription());
    }

    /** @test */
    public function it_returns_its_name(): void
    {
        self::assertEquals('::name::', $this->result->getName());
    }
}
