<?php

namespace Tests\Unit\Actions\Social;

use App\Actions\Social\DisconnectSocialAccountAction;
use App\Actions\User\UpdateUserAvatarAction;
use App\Exceptions\SocialAuthException;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class DisconnectSocialAccountActionTest extends TestCase
{
    use RefreshDatabase;

    private DisconnectSocialAccountAction $action;

    private UpdateUserAvatarAction $updateAvatarAction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->updateAvatarAction = Mockery::mock(UpdateUserAvatarAction::class);
        $this->action = new DisconnectSocialAccountAction($this->updateAvatarAction);
    }

    public function test_successfully_disconnects_social_account_when_user_has_password(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        SocialAccount::factory()->create([
            'user_id' => $user->id,
            'provider' => 'github',
        ]);

        SocialAccount::factory()->create([
            'user_id' => $user->id,
            'provider' => 'google',
        ]);

        $this->updateAvatarAction
            ->shouldReceive('updateToLatestProviderAvatar')
            ->once()
            ->with(Mockery::type(User::class))
            ->andReturnUsing(fn ($user) => $user);

        $result = $this->action->execute($user, 'github');

        $this->assertInstanceOf(User::class, $result);
        $this->assertDatabaseMissing('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'github',
        ]);
        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'google',
        ]);
    }

    public function test_successfully_disconnects_when_multiple_social_accounts_exist(): void
    {
        $user = User::factory()->create(['password' => null]);

        SocialAccount::factory()->create([
            'user_id' => $user->id,
            'provider' => 'github',
        ]);

        SocialAccount::factory()->create([
            'user_id' => $user->id,
            'provider' => 'google',
        ]);

        $this->updateAvatarAction
            ->shouldReceive('updateToLatestProviderAvatar')
            ->once()
            ->with(Mockery::type(User::class))
            ->andReturnUsing(fn ($user) => $user);

        $result = $this->action->execute($user, 'github');

        $this->assertInstanceOf(User::class, $result);
        $this->assertDatabaseMissing('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'github',
        ]);
        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'google',
        ]);
    }

    public function test_throws_exception_when_trying_to_disconnect_only_login_method(): void
    {
        $user = User::factory()->create(['password' => null]);

        SocialAccount::factory()->create([
            'user_id' => $user->id,
            'provider' => 'github',
        ]);

        $this->updateAvatarAction->shouldNotReceive('updateToLatestProviderAvatar');

        $this->expectException(SocialAuthException::class);
        $this->expectExceptionMessage('Cannot disconnect your only login method. Set a password first.');

        $this->action->execute($user, 'github');
    }

    public function test_allows_disconnection_when_user_has_password_and_single_social_account(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        SocialAccount::factory()->create([
            'user_id' => $user->id,
            'provider' => 'github',
        ]);

        $this->updateAvatarAction
            ->shouldReceive('updateToLatestProviderAvatar')
            ->once()
            ->with(Mockery::type(User::class))
            ->andReturnUsing(fn ($user) => $user);

        $result = $this->action->execute($user, 'github');

        $this->assertInstanceOf(User::class, $result);
        $this->assertDatabaseMissing('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'github',
        ]);
    }

    public function test_does_nothing_when_provider_does_not_exist(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        SocialAccount::factory()->create([
            'user_id' => $user->id,
            'provider' => 'github',
        ]);

        $this->updateAvatarAction
            ->shouldReceive('updateToLatestProviderAvatar')
            ->once()
            ->with(Mockery::type(User::class))
            ->andReturnUsing(fn ($user) => $user);

        $result = $this->action->execute($user, 'nonexistent');

        $this->assertInstanceOf(User::class, $result);
        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'github',
        ]);
    }

    public function test_updates_avatar_after_successful_disconnection(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        SocialAccount::factory()->create([
            'user_id' => $user->id,
            'provider' => 'github',
        ]);

        SocialAccount::factory()->create([
            'user_id' => $user->id,
            'provider' => 'google',
        ]);

        $this->updateAvatarAction
            ->shouldReceive('updateToLatestProviderAvatar')
            ->once()
            ->with(Mockery::type(User::class))
            ->andReturnUsing(fn ($user) => $user);

        $result = $this->action->execute($user, 'github');

        $this->assertInstanceOf(User::class, $result);
    }

    public function test_returns_fresh_user_instance(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        SocialAccount::factory()->create([
            'user_id' => $user->id,
            'provider' => 'github',
        ]);

        $this->updateAvatarAction
            ->shouldReceive('updateToLatestProviderAvatar')
            ->once()
            ->with(Mockery::type(User::class))
            ->andReturnUsing(fn ($user) => $user);

        $result = $this->action->execute($user, 'github');

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($user->id, $result->id);
    }
}
