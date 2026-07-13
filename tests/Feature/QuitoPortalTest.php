<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Section;
use App\Models\Link;
use App\Models\LinkClick;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class QuitoPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Configure default settings for testing
        Setting::set('site_name', 'QUITO 2026 TEST');
        Setting::set('primary_color', '#062B63');
        Setting::set('primary_dark_color', '#031D46');
        Setting::set('secondary_color', '#6CCBF2');
        Setting::set('coral_color', '#FF5964');
        Setting::set('yellow_color', '#FFBE26');
        Setting::set('bg_style', 'default');
    }

    public function test_guest_can_access_landing_page()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('QUITO 2026 TEST');
    }

    public function test_guest_is_redirected_when_accessing_direct_link()
    {
        $section = Section::create([
            'title' => 'Test Section',
            'slug' => 'test-section',
            'is_active' => true,
        ]);

        $link = Link::create([
            'section_id' => $section->id,
            'title' => 'Test Direct Link',
            'slug' => 'test-direct',
            'destination_url' => 'https://chat.whatsapp.com/direct-url',
            'link_type' => 'whatsapp',
            'redirect_mode' => 'direct',
            'is_active' => true,
        ]);

        $response = $this->get('/acceso/test-direct');
        $response->assertRedirect('https://chat.whatsapp.com/direct-url');
        
        // Assert click was registered and IP anonymized
        $this->assertEquals(1, LinkClick::count());
        $this->assertNotEquals('127.0.0.1', LinkClick::first()->anonymized_ip);
    }

    public function test_interstitial_page_is_shown_for_interstitial_link()
    {
        $section = Section::create([
            'title' => 'Test Section',
            'slug' => 'test-section',
            'is_active' => true,
        ]);

        $link = Link::create([
            'section_id' => $section->id,
            'title' => 'Test Interstitial Link',
            'slug' => 'test-interstitial',
            'destination_url' => 'https://chat.whatsapp.com/interstitial-url',
            'link_type' => 'whatsapp',
            'redirect_mode' => 'interstitial',
            'is_active' => true,
        ]);

        $response = $this->get('/acceso/test-interstitial');
        $response->assertStatus(200);
        $response->assertSee('Confirmar e ingresar al enlace');
    }

    public function test_link_shows_password_screen_when_protected()
    {
        $section = Section::create([
            'title' => 'Test Section',
            'slug' => 'test-section',
            'is_active' => true,
        ]);

        $link = new Link([
            'section_id' => $section->id,
            'title' => 'Test Protected Link',
            'slug' => 'test-protected',
            'destination_url' => 'https://chat.whatsapp.com/protected-url',
            'link_type' => 'whatsapp',
            'redirect_mode' => 'direct',
            'is_active' => true,
        ]);
        $link->setAccessCode('secretcode');
        $link->save();

        // Access should ask for password
        $response = $this->get('/acceso/test-protected');
        $response->assertStatus(200);
        $response->assertSee('Acceso Protegido');

        // Submit wrong password
        $response = $this->post('/acceso/test-protected/verificar', ['code' => 'wrong']);
        $response->assertSessionHasErrors('code');

        // Submit correct password
        $response = $this->post('/acceso/test-protected/verificar', ['code' => 'secretcode']);
        $response->assertRedirect('/acceso/test-protected');
    }

    public function test_link_limits_max_clicks()
    {
        $section = Section::create([
            'title' => 'Test Section',
            'slug' => 'test-section',
            'is_active' => true,
        ]);

        $link = Link::create([
            'section_id' => $section->id,
            'title' => 'Test Limit Link',
            'slug' => 'test-limit',
            'destination_url' => 'https://chat.whatsapp.com/limit-url',
            'link_type' => 'whatsapp',
            'redirect_mode' => 'direct',
            'max_clicks' => 1,
            'is_active' => true,
        ]);

        // First click
        $response = $this->get('/acceso/test-limit');
        $response->assertRedirect('https://chat.whatsapp.com/limit-url');

        // Second click -> Forbidden/Unavailable
        $response = $this->get('/acceso/test-limit');
        $response->assertStatus(403);
        $response->assertSee('Acceso No Disponible');
    }

    public function test_non_auth_cannot_access_admin()
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_editor_can_access_admin_dashboard()
    {
        $editor = User::create([
            'name' => 'Editor User',
            'email' => 'editor@test.com',
            'password' => Hash::make('secret123'),
            'role' => 'editor',
            'is_active' => true,
        ]);

        $response = $this->actingAs($editor)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Resumen');
    }

    public function test_editor_cannot_access_settings_or_users()
    {
        $editor = User::create([
            'name' => 'Editor User',
            'email' => 'editor@test.com',
            'password' => Hash::make('secret123'),
            'role' => 'editor',
            'is_active' => true,
        ]);

        $response = $this->actingAs($editor)->get('/admin/configuracion');
        $response->assertStatus(403);

        $response = $this->actingAs($editor)->get('/admin/users');
        $response->assertStatus(403);
    }
}
