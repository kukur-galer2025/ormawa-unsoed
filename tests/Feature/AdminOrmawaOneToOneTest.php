<?php

namespace Tests\Feature;

use App\Models\Ormawa;
use App\Models\OrmawaAdmin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminOrmawaOneToOneTest extends TestCase
{
    use RefreshDatabase;

    private User $superadmin;
    private Ormawa $ormawa1;
    private Ormawa $ormawa2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superadmin = User::create([
            'name' => 'Superadmin',
            'email' => 'superadmin@test.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'is_active' => true,
        ]);

        $this->ormawa1 = Ormawa::create([
            'nama' => 'BEM UNSOED',
            'slug' => 'bem-unsoed',
            'tingkat' => 'universitas',
            'is_active' => true,
        ]);

        $this->ormawa2 = Ormawa::create([
            'nama' => 'BEM FT',
            'slug' => 'bem-ft',
            'tingkat' => 'fakultas',
            'is_active' => true,
        ]);
    }

    /**
     * Test: Tidak bisa membuat Admin kedua untuk organisasi yang sudah punya Admin.
     */
    public function test_cannot_create_second_admin_for_ormawa_that_already_has_one(): void
    {
        // Create first admin for ormawa1
        $admin1 = User::create([
            'name' => 'Admin Pertama',
            'email' => 'admin1@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
        OrmawaAdmin::create(['user_id' => $admin1->id, 'ormawa_id' => $this->ormawa1->id]);

        // Attempt to create second admin for the same ormawa1
        $response = $this->actingAs($this->superadmin)->post(route('superadmin.admin.store'), [
            'name' => 'Admin Kedua',
            'email' => 'admin2@test.com',
            'password' => 'password123',
            'ormawa_id' => $this->ormawa1->id,
        ]);

        $response->assertSessionHasErrors('ormawa_id');
        $this->assertDatabaseMissing('users', ['email' => 'admin2@test.com']);
    }

    /**
     * Test: Mengubah nama atau email Admin tanpa mengganti organisasi tetap berhasil.
     */
    public function test_can_update_admin_name_and_email_without_changing_ormawa(): void
    {
        $admin = User::create([
            'name' => 'Admin Lama',
            'email' => 'admin.lama@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
        OrmawaAdmin::create(['user_id' => $admin->id, 'ormawa_id' => $this->ormawa1->id]);

        $response = $this->actingAs($this->superadmin)->put(route('superadmin.admin.update', $admin), [
            'name' => 'Admin Baru',
            'email' => 'admin.baru@test.com',
            'ormawa_id' => $this->ormawa1->id, // same ormawa
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('superadmin.admin.index'));
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'name' => 'Admin Baru',
            'email' => 'admin.baru@test.com',
        ]);
        $this->assertDatabaseHas('admin_ormawa', [
            'user_id' => $admin->id,
            'ormawa_id' => $this->ormawa1->id,
        ]);
    }

    /**
     * Test: Tidak bisa memindahkan Admin ke organisasi yang sudah punya Admin lain.
     */
    public function test_cannot_move_admin_to_ormawa_that_already_has_another_admin(): void
    {
        // Admin A assigned to ormawa1
        $adminA = User::create([
            'name' => 'Admin A',
            'email' => 'adminA@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
        OrmawaAdmin::create(['user_id' => $adminA->id, 'ormawa_id' => $this->ormawa1->id]);

        // Admin B assigned to ormawa2
        $adminB = User::create([
            'name' => 'Admin B',
            'email' => 'adminB@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
        OrmawaAdmin::create(['user_id' => $adminB->id, 'ormawa_id' => $this->ormawa2->id]);

        // Try to move Admin B to ormawa1 (already has Admin A)
        $response = $this->actingAs($this->superadmin)->put(route('superadmin.admin.update', $adminB), [
            'name' => 'Admin B',
            'email' => 'adminB@test.com',
            'ormawa_id' => $this->ormawa1->id,
        ]);

        $response->assertSessionHasErrors('ormawa_id');
        // Verify the original assignment is still intact
        $this->assertDatabaseHas('admin_ormawa', [
            'user_id' => $adminB->id,
            'ormawa_id' => $this->ormawa2->id,
        ]);
    }

    /**
     * Test: Insert langsung ke admin_ormawa dengan ormawa_id yang sama ditolak database.
     */
    public function test_database_rejects_duplicate_ormawa_id_in_admin_ormawa(): void
    {
        $user1 = User::create([
            'name' => 'User 1',
            'email' => 'user1@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $user2 = User::create([
            'name' => 'User 2',
            'email' => 'user2@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        OrmawaAdmin::create(['user_id' => $user1->id, 'ormawa_id' => $this->ormawa1->id]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        // Direct insert with same ormawa_id should fail at DB level
        DB::table('admin_ormawa')->insert([
            'user_id' => $user2->id,
            'ormawa_id' => $this->ormawa1->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
