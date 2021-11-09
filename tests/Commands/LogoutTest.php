<?php declare(strict_types=1);

namespace LivewireUI\Spotlight\Tests\Commands;

use Illuminate\Contracts\Auth\StatefulGuard;
use LivewireUI\Spotlight\Commands\Logout;
use LivewireUI\Spotlight\Spotlight;
use PHPUnit\Framework\TestCase;

class LogoutTest extends TestCase
{
    /** @test */
    public function it_logs_out_the_current_user(): void
    {
        $spotlight = $this->createMock(Spotlight::class);
        $guardMock = $this->createMock(StatefulGuard::class);
        $guardMock->expects($this->once())->method('logout');

        $command = new Logout();

        $command->execute($spotlight, $guardMock);
    }

    /** @test */
    public function it_redirects_after_logout(): void
    {
        $spotlight = $this->createMock(Spotlight::class);
        $spotlight->expects($this->once())
            ->method('redirect')
            ->with('/');
        $guardMock = $this->createMock(StatefulGuard::class);

        $command = new Logout();

        $command->execute($spotlight, $guardMock);
    }
}
