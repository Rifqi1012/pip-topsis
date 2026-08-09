<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiswaImportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed necessary data
        $this->artisan('db:seed', ['--class' => 'KriteriaSeeder']);
        $this->artisan('db:seed', ['--class' => 'SubKriteriaSeeder']);
    }

    public function test_wali_kelas_can_access_import_page()
    {
        $user = User::factory()->create(['role' => 'wali_kelas']);
        
        $response = $this->actingAs($user)->get('/siswa/import');
        
        $response->assertStatus(200);
        $response->assertViewIs('siswa-import.index');
    }

    public function test_tata_usaha_cannot_access_import_page()
    {
        $user = User::factory()->create(['role' => 'tata_usaha']);
        
        $response = $this->actingAs($user)->get('/siswa/import');
        
        $response->assertStatus(403);
    }

    public function test_kepala_sekolah_cannot_access_import_page()
    {
        $user = User::factory()->create(['role' => 'kepala_sekolah']);
        
        $response = $this->actingAs($user)->get('/siswa/import');
        
        $response->assertStatus(403);
    }
}
