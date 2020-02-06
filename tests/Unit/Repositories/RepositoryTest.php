<?php

namespace Tests\Unit\Repositories;

use App\Models\RestaurantInterface;
use App\Repositories\Repository;
use Mockery;
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase
{
    public function testGetNextSequence()
    {
        $max = 1;
        $mock = Mockery::mock(RestaurantInterface::class);
        $mock->shouldReceive('getKeyName')->andReturn('id');
        $mock->shouldReceive('max')->andReturn($max);

        $repository = $this->getMockForAbstractClass(Repository::class, [$mock]);

        $this->assertEquals($max + 1, $repository->getNextSequence());
    }
}
