<?php

namespace Tests\Unit\Services;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Modules\Auth\Exceptions\SocialiteException;
use Modules\Auth\Models\SocialAccount;
use Modules\Auth\Services\SocialiteService;
use Tests\TestCase;

class SocialiteServiceTest extends TestCase
{
    use RefreshDatabase;

    private SocialiteService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SocialiteService;
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    private function mockSocialiteUser(array $data = []): SocialiteUser
    {
        $faker = fake();

        $defaults = [
            'email' => $faker->unique()->safeEmail(),
            'id' => $faker->uuid(),
            'name' => $faker->name(),
            'nickname' => $faker->userName(),
            'avatar' => $faker->imageUrl(200, 200, 'people'),
            'token' => $faker->sha256(),
            'refreshToken' => $faker->sha256(),
        ];

        $data = array_merge($defaults, $data);

        $mockUser = \Mockery::mock(SocialiteUser::class);
        $mockUser->shouldReceive('getEmail')->andReturn($data['email']);
        $mockUser->shouldReceive('getId')->andReturn($data['id']);
        $mockUser->shouldReceive('getName')->andReturn($data['name']);
        $mockUser->shouldReceive('getNickname')->andReturn($data['nickname']);
        $mockUser->shouldReceive('getAvatar')->andReturn($data['avatar']);
        $mockUser->token = $data['token'];
        $mockUser->refreshToken = $data['refreshToken'];

        return $mockUser;
    }

    private function mockSocialiteDriver(string $provider, SocialiteUser $user): void
    {
        Socialite::shouldReceive('driver')
            ->with($provider)
            ->andReturnSelf()
            ->shouldReceive('user')
            ->andReturn($user);
    }

    private function createSocialAccount(User $user, array $attributes = []): SocialAccount
    {
        $faker = fake();

        return SocialAccount::create(array_merge([
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_id' => $faker->uuid(),
            'provider_token' => $faker->sha256(),
            'provider_refresh_token' => null,
            'provider_avatar_url' => null,
            'last_login_at' => now(),
        ], $attributes));
    }

    public function test_creates_new_user_and_social_account_for_first_time_login(): void
    {
        $email = fake()->safeEmail();
        $name = fake()->name();
        $avatar = fake()->imageUrl(200, 200, 'people');
        $providerId = fake()->uuid();
        $token = fake()->sha256();
        $refreshToken = fake()->sha256();

        $mockUser = $this->mockSocialiteUser([
            'email' => $email,
            'name' => $name,
            'avatar' => $avatar,
            'id' => $providerId,
            'token' => $token,
            'refreshToken' => $refreshToken,
        ]);
        $this->mockSocialiteDriver('google', $mockUser);

        $user = $this->service->handleCallback('google');

        $this->assertEquals($email, $user->email);
        $this->assertEquals($name, $user->name);
        $this->assertEquals($avatar, $user->avatar_url);
        $this->assertNotNull($user->email_verified_at);
        $this->assertNotNull($user->password);

        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_id' => $providerId,
            'provider_token' => $token,
            'provider_refresh_token' => $refreshToken,
        ]);
    }

    public function test_updates_existing_social_account_on_return_login(): void
    {
        $email = fake()->safeEmail();
        $oldToken = fake()->sha256();
        $oldRefresh = fake()->sha256();
        $oldAvatar = fake()->imageUrl();
        $newToken = fake()->sha256();
        $newRefresh = fake()->sha256();
        $newAvatar = fake()->imageUrl();
        $providerId = fake()->uuid();

        $user = User::factory()->create(['email' => $email]);
        $socialAccount = $this->createSocialAccount($user, [
            'provider_id' => $providerId,
            'provider_token' => $oldToken,
            'provider_refresh_token' => $oldRefresh,
            'provider_avatar_url' => $oldAvatar,
        ]);

        $mockUser = $this->mockSocialiteUser([
            'email' => $email,
            'id' => $providerId,
            'avatar' => $newAvatar,
            'token' => $newToken,
            'refreshToken' => $newRefresh,
        ]);
        $this->mockSocialiteDriver('google', $mockUser);

        $returnedUser = $this->service->handleCallback('google');

        $this->assertEquals($user->id, $returnedUser->id);

        $socialAccount->refresh();
        $this->assertEquals($newToken, $socialAccount->provider_token);
        $this->assertEquals($newRefresh, $socialAccount->provider_refresh_token);
        $this->assertEquals($newAvatar, $socialAccount->provider_avatar_url);

        $returnedUser->refresh();
        $this->assertEquals($newAvatar, $returnedUser->avatar_url);
    }

    public function test_links_social_account_to_existing_user_with_matching_email(): void
    {
        $email = fake()->safeEmail();
        $providerId = fake()->uuid();

        $user = User::factory()->create([
            'email' => $email,
            'password' => Hash::make('password'),
        ]);

        $mockUser = $this->mockSocialiteUser([
            'email' => $email,
            'id' => $providerId,
        ]);
        $this->mockSocialiteDriver('github', $mockUser);

        $returnedUser = $this->service->handleCallback('github');

        $this->assertEquals($user->id, $returnedUser->id);

        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'github',
            'provider_id' => $providerId,
        ]);
    }

    public function test_throws_exception_for_missing_email(): void
    {
        $mockUser = $this->mockSocialiteUser(['email' => null]);
        $this->mockSocialiteDriver('google', $mockUser);

        $this->expectException(SocialiteException::class);
        $this->service->handleCallback('google');
    }

    public function test_throws_exception_for_missing_provider_id(): void
    {
        $mockUser = $this->mockSocialiteUser(['id' => null]);
        $this->mockSocialiteDriver('google', $mockUser);

        $this->expectException(SocialiteException::class);
        $this->service->handleCallback('google');
    }

    public function test_throws_exception_for_invalid_email_format(): void
    {
        $mockUser = $this->mockSocialiteUser(['email' => 'not-an-email']);
        $this->mockSocialiteDriver('google', $mockUser);

        $this->expectException(SocialiteException::class);
        $this->service->handleCallback('google');
    }

    public function test_handles_null_avatar_url(): void
    {
        $mockUser = $this->mockSocialiteUser(['avatar' => null]);
        $this->mockSocialiteDriver('google', $mockUser);

        $user = $this->service->handleCallback('google');

        $this->assertNull($user->avatar_url);
    }

    public function test_handles_invalid_avatar_url(): void
    {
        $mockUser = $this->mockSocialiteUser(['avatar' => 'not-a-valid-url']);
        $this->mockSocialiteDriver('google', $mockUser);

        $user = $this->service->handleCallback('google');

        $this->assertNull($user->avatar_url);
    }

    public function test_validates_and_sets_valid_avatar_url(): void
    {
        $validAvatar = fake()->imageUrl();

        $mockUser = $this->mockSocialiteUser(['avatar' => $validAvatar]);
        $this->mockSocialiteDriver('google', $mockUser);

        $user = $this->service->handleCallback('google');

        $this->assertEquals($validAvatar, $user->avatar_url);
    }

    public function test_uses_nickname_when_name_is_null(): void
    {
        $nickname = fake()->userName();

        $mockUser = $this->mockSocialiteUser([
            'name' => null,
            'nickname' => $nickname,
        ]);
        $this->mockSocialiteDriver('google', $mockUser);

        $user = $this->service->handleCallback('google');

        $this->assertEquals($nickname, $user->name);
    }

    public function test_disconnects_provider_when_multiple_methods_exist(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password')]);
        $this->createSocialAccount($user);

        $this->service->disconnectProvider($user, 'google');

        $this->assertDatabaseMissing('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'google',
        ]);
    }

    public function test_disconnects_provider_when_multiple_social_accounts_exist(): void
    {
        $user = User::factory()->create(['password' => null]);
        $this->createSocialAccount($user, ['provider' => 'google']);
        $this->createSocialAccount($user, ['provider' => 'github', 'provider_id' => '67890']);

        $this->service->disconnectProvider($user, 'google');

        $this->assertDatabaseMissing('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'google',
        ]);
        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'github',
        ]);
    }

    public function test_cannot_disconnect_only_authentication_method(): void
    {
        $user = User::factory()->create(['password' => null]);
        $this->createSocialAccount($user);

        $this->expectException(SocialiteException::class);
        $this->service->disconnectProvider($user, 'google');
    }

    public function test_throws_exception_when_provider_not_connected(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password')]);
        $this->createSocialAccount($user, ['provider' => 'google']);

        $this->expectException(SocialiteException::class);
        $this->service->disconnectProvider($user, 'github');
    }
}
