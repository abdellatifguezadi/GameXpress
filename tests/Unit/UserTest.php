<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_user()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ];

        $user = User::create($userData);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);
        
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_can_update_user()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com'
        ]);

        $user->update([
            'name' => 'New Name',
            'email' => 'new@example.com'
        ]);

        $user->refresh();

        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('new@example.com', $user->email);
    }

    public function test_can_delete_user()
    {
        $user = User::factory()->create();
        
        $user->delete();

        $this->assertSoftDeleted('users', [
            'id' => $user->id
        ]);
    }

    public function test_can_restore_deleted_user()
    {
        $user = User::factory()->create();
        $user->delete();

        $this->assertSoftDeleted($user);

        $user->restore();
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'deleted_at' => null
        ]);
    }

    public function test_can_assign_role_to_user()
    {
        Role::create(['name' => 'super_admin']);
        
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        $this->assertTrue($user->hasRole('super_admin'));
    }

    
    public function test_password_is_hashed_when_updated()
    {
        $user = User::factory()->create();
        
        $user->update([
            'password' => 'newpassword123'
        ]);

        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }
}
