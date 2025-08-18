<?php

namespace Tests\Unit\Actions\Social;

use App\Actions\Social\LinkSocialAccountAction;
use App\Actions\User\UpdateUserAvatarAction;
use App\Exceptions\SocialAuthException;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class LinkSocialAccountActionTest extends TestCase
{
    use RefreshDatabase;

    private LinkSocialAccountAction $action;

    private UpdateUserAvatarAction $updateAvatarAction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->updateAvatarAction = Mockery::mock(UpdateUserAvatarAction::class);
        $this->action = new LinkSocialAccountAction($this->updateAvatarAction);
    }

    public function test_creates_new_user_when_no_existing_account_or_user(): void
    {
        $socialUser = $this->createMockSocialUser([
            'id' => '12345',
            'email' => 'test@example.com',
            'name' => 'Test User',
            'avatar' => 'https://example.com/avatar.jpg',
            'token' => 'access_token',
            'refreshToken' => 'refresh_token',
        ]);

        $this->updateAvatarAction->shouldNotReceive('execute');

        $user = $this->action->execute('github', $socialUser);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('https://example.com/avatar.jpg', $user->avatar_url);
        $this->assertNotNull($user->email_verified_at);
        $this->assertNotNull($user->password);

        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'github',
            'provider_id' => '12345',
            'provider_token' => 'access_token',
            'provider_refresh_token' => 'refresh_token',
            'provider_avatar_url' => 'https://example.com/avatar.jpg',
        ]);
    }

    public function test_links_to_existing_user_with_same_email(): void
    {
        $existingUser = User::factory()->create([
            'email' => 'test@example.com',
            'avatar_url' => 'old_avatar.jpg',
        ]);

        $socialUser = $this->createMockSocialUser([
            'id' => '12345',
            'email' => 'test@example.com',
            'name' => 'Test User',
            'avatar' => 'https://example.com/new_avatar.jpg',
            'token' => 'access_token',
        ]);

        $this->updateAvatarAction
            ->shouldReceive('execute')
            ->once()
            ->with($existingUser, 'https://example.com/new_avatar.jpg')
            ->andReturn($existingUser);

        $user = $this->action->execute('github', $socialUser);

        $this->assertEquals($existingUser->id, $user->id);

        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $existingUser->id,
            'provider' => 'github',
            'provider_id' => '12345',
        ]);
    }

    public function test_updates_existing_social_account(): void
    {
        $user = User::factory()->create();
        $socialAccount = SocialAccount::factory()->create([
            'user_id' => $user->id,
            'provider' => 'github',
            'provider_id' => '12345',
            'provider_token' => 'old_token',
            'provider_avatar_url' => 'old_avatar.jpg',
        ]);

        $socialUser = $this->createMockSocialUser([
            'id' => '12345',
            'email' => $user->email,
            'name' => $user->name,
            'avatar' => 'https://example.com/new_avatar.jpg',
            'token' => 'new_access_token',
            'refreshToken' => 'new_refresh_token',
        ]);

        $this->updateAvatarAction
            ->shouldReceive('execute')
            ->once()
            ->with($user, 'https://example.com/new_avatar.jpg')
            ->andReturn($user);

        $returnedUser = $this->action->execute('github', $socialUser);

        $this->assertEquals($user->id, $returnedUser->id);

        $socialAccount->refresh();
        $this->assertEquals('new_access_token', $socialAccount->provider_token);
        $this->assertEquals('new_refresh_token', $socialAccount->provider_refresh_token);
        $this->assertEquals('https://example.com/new_avatar.jpg', $socialAccount->provider_avatar_url);
        $this->assertNotNull($socialAccount->last_login_at);
    }

    public function test_handles_null_avatar_url(): void
    {
        $socialUser = $this->createMockSocialUser([
            'id' => '12345',
            'email' => 'test@example.com',
            'name' => 'Test User',
            'avatar' => null,
            'token' => 'access_token',
        ]);

        $this->updateAvatarAction->shouldNotReceive('execute');

        $user = $this->action->execute('github', $socialUser);

        $this->assertNull($user->avatar_url);

        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider_avatar_url' => null,
        ]);
    }

    public function test_handles_invalid_avatar_url(): void
    {
        $socialUser = $this->createMockSocialUser([
            'id' => '12345',
            'email' => 'test@example.com',
            'name' => 'Test User',
            'avatar' => 'not-a-valid-url',
            'token' => 'access_token',
        ]);

        $this->updateAvatarAction->shouldNotReceive('execute');

        $user = $this->action->execute('github', $socialUser);

        $this->assertNull($user->avatar_url);

        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider_avatar_url' => null,
        ]);
    }

    public function test_uses_nickname_when_name_is_null(): void
    {
        $socialUser = $this->createMockSocialUser([
            'id' => '12345',
            'email' => 'test@example.com',
            'name' => null,
            'nickname' => 'testuser',
            'avatar' => null,
            'token' => 'access_token',
        ]);

        $this->updateAvatarAction->shouldNotReceive('execute');

        $user = $this->action->execute('github', $socialUser);

        $this->assertEquals('testuser', $user->name);
    }

    public function test_throws_exception_for_invalid_social_user_missing_email(): void
    {
        $socialUser = $this->createMockSocialUser([
            'id' => '12345',
            'email' => null,
            'name' => 'Test User',
        ]);

        $this->expectException(SocialAuthException::class);
        $this->expectExceptionMessage('Invalid social account data received.');

        $this->action->execute('github', $socialUser);
    }

    public function test_throws_exception_for_invalid_social_user_missing_id(): void
    {
        $socialUser = $this->createMockSocialUser([
            'id' => null,
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);

        $this->expectException(SocialAuthException::class);
        $this->expectExceptionMessage('Invalid social account data received.');

        $this->action->execute('github', $socialUser);
    }

    public function test_throws_exception_for_invalid_email_format(): void
    {
        $socialUser = $this->createMockSocialUser([
            'id' => '12345',
            'email' => 'invalid-email',
            'name' => 'Test User',
        ]);

        $this->expectException(SocialAuthException::class);
        $this->expectExceptionMessage('Invalid social account data received.');

        $this->action->execute('github', $socialUser);
    }

    private function createMockSocialUser(array $data): object
    {
        $mock = Mockery::mock();
        $mock->shouldReceive('getId')->andReturn($data['id'] ?? null);
        $mock->shouldReceive('getEmail')->andReturn($data['email'] ?? null);
        $mock->shouldReceive('getName')->andReturn($data['name'] ?? null);
        $mock->shouldReceive('getNickname')->andReturn($data['nickname'] ?? null);
        $mock->shouldReceive('getAvatar')->andReturn($data['avatar'] ?? null);

        $mock->token = $data['token'] ?? null;
        $mock->refreshToken = $data['refreshToken'] ?? null;

        return $mock;
    }
}
