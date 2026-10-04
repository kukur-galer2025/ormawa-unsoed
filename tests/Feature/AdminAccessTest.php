<?php

namespace Tests\Feature;

use App\Models\Ormawa;
use App\Models\OrmawaAdmin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    private User $adminWithOrmawa;
    private User $adminWithoutOrmawa;
    private Ormawa $ormawa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ormawa = Ormawa::create([
            'nama' => 'BEM UNSOED',
            'slug' => 'bem-unsoed',
            'tingkat' => 'universitas',
            'is_active' => true,
        ]);

        $this->adminWithOrmawa = User::create([
            'name' => 'Admin With Ormawa',
            'email' => 'admin_with@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
        OrmawaAdmin::create(['user_id' => $this->adminWithOrmawa->id, 'ormawa_id' => $this->ormawa->id]);

        $this->adminWithoutOrmawa = User::create([
            'name' => 'Admin Without Ormawa',
            'email' => 'admin_without@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_admin_with_ormawa_can_access_dashboard_prestasi_and_proker(): void
    {
        $this->actingAs($this->adminWithOrmawa);

        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');

        $response = $this->get(route('admin.prestasi.index'));
        $response->assertStatus(200);

        $response = $this->get(route('admin.proker.index'));
        $response->assertStatus(200);
    }

    public function test_admin_without_ormawa_is_redirected_or_shown_no_ormawa_page(): void
    {
        $this->actingAs($this->adminWithoutOrmawa);

        // Dashboard should show no-ormawa view
        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.no-ormawa');

        // Other pages should redirect to dashboard with error
        $response = $this->get(route('admin.prestasi.index'));
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error', 'Anda belum di-assign ke organisasi manapun.');

        $response = $this->get(route('admin.proker.index'));
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error', 'Anda belum di-assign ke organisasi manapun.');
    }
}
