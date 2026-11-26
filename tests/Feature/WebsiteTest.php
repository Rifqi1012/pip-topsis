<?php

namespace Tests\Feature;

use App\Models\HasilTopsis;
use App\Models\Kelas;
use App\Models\Kriteria;
use App\Models\Pengaturan;
use App\Models\Siswa;
use App\Models\SubKriteria;
use App\Models\TahunAjaran;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    private function wali(): User
    {
        return User::where('role', 'wali_kelas')->firstOrFail();
    }

    private function kepala(): User
    {
        return User::where('role', 'kepala_sekolah')->firstOrFail();
    }

    private function student(): Siswa
    {
        return Siswa::create([
            'user_id' => $this->wali()->id, 'kode_siswa' => 'SW-0001',
            'nisn' => '0012345678', 'nama_siswa' => 'Siswa Uji',
            'kelas' => '1', 'periode' => '2026/2027', 'status_data' => 'submitted',
        ]);
    }

    public function test_public_pages_and_guest_redirects(): void
    {
        foreach (['/login', '/register', '/forgot-password', '/up'] as $url) {
            $this->get($url)->assertOk();
        }

        foreach (['/dashboard', '/siswas', '/penilaian-siswa', '/kelas', '/kriteria',
            '/users', '/tahun-ajaran', '/pengaturan', '/topsis', '/laporan/pdf',
            '/laporan/excel', '/siswa/import', '/profile'] as $url) {
            $this->get($url)->assertRedirect('/login');
        }
    }

    public function test_kepala_pages_render_with_empty_student_data(): void
    {
        $this->actingAs($this->kepala());
        foreach (['/dashboard', '/kepala-sekolah/dashboard', '/siswas',
            '/penilaian-siswa', '/kelas', '/kriteria?search=Ayah', '/users',
            '/users/create', '/tahun-ajaran', '/pengaturan', '/topsis', '/profile'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_wali_pages_render_and_admin_pages_are_forbidden(): void
    {
        $siswa = $this->student();
        $this->actingAs($this->wali());
        foreach (['/dashboard', '/wali-kelas/dashboard', '/siswas', '/siswas/create',
            "/siswas/{$siswa->id}", "/siswas/{$siswa->id}/edit", '/penilaian-siswa',
            "/penilaian-siswa/{$siswa->id}/edit", '/siswa/import', '/tahun-ajaran', '/profile'] as $url) {
            $this->get($url)->assertOk();
        }
        foreach (['/kepala-sekolah/dashboard', '/kelas', '/kriteria', '/users',
            '/pengaturan', '/topsis', '/topsis/transparansi', '/laporan/pdf', '/laporan/excel'] as $url) {
            $this->get($url)->assertForbidden();
        }
        $this->post('/kelas', ['nama' => '9'])->assertForbidden();
        $this->post('/topsis/calculate')->assertForbidden();
        $this->post('/pengaturan', ['kuota_penerima' => 99])->assertForbidden();
    }

    public function test_student_lifecycle_and_ownership(): void
    {
        $data = ['nisn' => '0012345678', 'nama_siswa' => 'Siswa Uji', 'kelas' => '1',
            'periode' => '2026/2027', 'alamat' => 'Bandung', 'status_data' => 'draft'];
        $this->actingAs($this->wali())->post('/siswas', $data)->assertSessionHasNoErrors()->assertRedirect(route('siswas.index'));
        $siswa = Siswa::firstOrFail();
        $this->assertSame($this->wali()->id, $siswa->user_id);
        $this->post('/siswas', $data)->assertSessionHasErrors('nisn');
        $this->put("/siswas/{$siswa->id}", [...$data, 'nama_siswa' => 'Nama Baru'])->assertSessionHasNoErrors();
        $this->assertSame('Nama Baru', $siswa->fresh()->nama_siswa);

        $other = User::factory()->create(['role' => 'wali_kelas']);
        $this->actingAs($other)->get('/siswas')->assertDontSee('Nama Baru');
        $this->get("/siswas/{$siswa->id}")->assertForbidden();
        $this->get("/siswas/{$siswa->id}/edit")->assertForbidden();
        $this->put("/siswas/{$siswa->id}", $data)->assertForbidden();
        $this->delete("/siswas/{$siswa->id}")->assertForbidden();
        $this->get("/penilaian-siswa/{$siswa->id}/edit")->assertForbidden();

        $this->actingAs($this->kepala())->get("/siswas/{$siswa->id}")->assertOk();
        $this->get("/siswas/{$siswa->id}/edit")->assertForbidden();
        $this->actingAs($this->wali())->delete("/siswas/{$siswa->id}")->assertRedirect(route('siswas.index'));
        $this->assertSoftDeleted($siswa);
    }

    public function test_penilaian_calculation_transparency_and_reports(): void
    {
        $siswa = $this->student();
        $penilaian = Kriteria::with('subKriterias')->get()
            ->mapWithKeys(fn ($k) => [$k->id => $k->subKriterias->first()->id])->all();
        $this->actingAs($this->wali())->put("/penilaian-siswa/{$siswa->id}", ['penilaian' => []])->assertSessionHasErrors();
        $wrong = $penilaian;
        $wrong[array_key_first($wrong)] = end($penilaian);
        $this->put("/penilaian-siswa/{$siswa->id}", ['penilaian' => $wrong])->assertSessionHasErrors();
        $this->put("/penilaian-siswa/{$siswa->id}", ['penilaian' => $penilaian])->assertSessionHasNoErrors();
        $this->assertDatabaseCount('penilaian_siswas', count($penilaian));
        $this->put("/penilaian-siswa/{$siswa->id}", ['penilaian' => $penilaian])->assertSessionHasNoErrors();
        $this->assertDatabaseCount('penilaian_siswas', count($penilaian));

        $this->actingAs($this->kepala())->post('/topsis/calculate', ['kelas' => '1', 'periode' => '2026/2027'])->assertSessionHas('success');
        $this->assertDatabaseHas('hasil_topsis', ['siswa_id' => $siswa->id, 'ranking' => 1]);
        foreach (['/siswas?search=Siswa&kelas=1&periode=2026%2F2027',
            '/penilaian-siswa?search=Siswa&kelas=1',
            '/topsis?kelas=1&periode=2026%2F2027&status=direkomendasikan',
            '/topsis/transparansi?kelas=1&periode=2026%2F2027'] as $url) {
            $this->get($url)->assertOk()->assertSee('Siswa Uji');
        }
        $this->get('/laporan/pdf?kelas=1&periode=2026%2F2027')->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->get('/laporan/excel?status=direkomendasikan')->assertOk()->assertDownload();
    }

    public function test_master_data_and_user_management(): void
    {
        $this->actingAs($this->kepala());
        $this->post('/kelas', ['nama' => '9A'])->assertSessionHasNoErrors();
        $kelas = Kelas::where('nama', '9A')->firstOrFail();
        $this->put("/kelas/{$kelas->id}", ['nama' => '9B'])->assertSessionHasNoErrors();
        $this->delete("/kelas/{$kelas->id}")->assertSessionHasNoErrors();
        $this->post('/tahun-ajaran', ['tahun_mulai' => 2026, 'tahun_selesai' => 2027])->assertSessionHasNoErrors();
        $tahun = TahunAjaran::firstOrFail();
        $this->delete("/tahun-ajaran/{$tahun->id}")->assertSessionHasNoErrors();
        $this->post('/kriteria', ['kode' => 'C9', 'nama_kriteria' => 'Uji', 'atribut' => 'Benefit', 'bobot' => 2])->assertSessionHasNoErrors();
        $kriteria = Kriteria::where('kode', 'C9')->firstOrFail();
        $this->put("/kriteria/{$kriteria->id}", ['kode' => 'C9', 'nama_kriteria' => 'Diperbarui', 'atribut' => 'Benefit', 'bobot' => 3])->assertSessionHasNoErrors();
        $this->post("/kriteria/{$kriteria->id}/sub-kriteria", ['nama_sub_kriteria' => 'Sub Uji', 'nilai' => 5])->assertSessionHasNoErrors();
        $sub = SubKriteria::where('kriteria_id', $kriteria->id)->firstOrFail();
        $this->put("/sub-kriteria/{$sub->id}", ['nama_sub_kriteria' => 'Sub Baru', 'nilai' => 4])->assertSessionHasNoErrors();
        $this->delete("/sub-kriteria/{$sub->id}")->assertSessionHasNoErrors();
        $this->delete("/kriteria/{$kriteria->id}")->assertSessionHasNoErrors();
        $this->post('/pengaturan', ['kuota_penerima' => 0])->assertSessionHasErrors('kuota_penerima');
        $this->post('/pengaturan', ['kuota_penerima' => 5])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('pengaturans', ['key' => 'kuota_penerima', 'value' => '5']);

        $data = ['name' => 'Guru Uji', 'email' => 'guru@example.test', 'password' => 'test-password', 'password_confirmation' => 'test-password', 'role' => 'wali_kelas'];
        $this->post('/users', $data)->assertSessionHasNoErrors();
        $guru = User::where('email', $data['email'])->firstOrFail();
        $this->get("/users/{$guru->id}/edit")->assertOk();
        $this->put("/users/{$guru->id}", [...$data, 'name' => 'Guru Baru'])->assertSessionHasNoErrors();
        $this->patch("/users/{$guru->id}/toggle-status")->assertSessionHas('success');
        $this->assertFalse((bool) $guru->fresh()->is_active);
        $this->patch('/users/'.$this->kepala()->id.'/toggle-status')->assertSessionHas('error');
    }

    public function test_kriteria_normalization_rejects_existing_code_before_database_insert(): void
    {
        $this->actingAs($this->kepala())->post('/kriteria', [
            'kode' => 'c1', 'nama_kriteria' => 'Duplikat', 'atribut' => 'Benefit', 'bobot' => 3,
        ])->assertSessionHasErrors('kode');
        $this->assertDatabaseCount('kriterias', 8);
    }

    public function test_invalid_calculation_filters_return_validation_errors(): void
    {
        $this->actingAs($this->kepala())->postJson('/topsis/calculate', ['kelas' => ['invalid']])->assertUnprocessable();
        $this->getJson('/topsis/transparansi?periode[]=invalid')->assertUnprocessable();
    }

    public function test_dashboard_counts_recommendations_across_classes_and_complete_assessments(): void
    {
        $siswa = $this->student();
        $other = $siswa->replicate();
        $other->fill(['kode_siswa' => 'SW-0002', 'nisn' => '0023456789', 'kelas' => '2A'])->save();
        foreach ([$siswa, $other] as $student) {
            HasilTopsis::create(['siswa_id' => $student->id, 'ranking' => 1, 'nilai_preferensi' => 1, 'd_plus' => 0, 'd_minus' => 1]);
        }
        Pengaturan::where('key', 'kuota_penerima')->update(['value' => '1']);
        $this->actingAs($this->kepala())->get('/dashboard')
            ->assertViewHas('jumlahPenerima', 2)->assertViewHas('jumlahPenilaian', 0);
        foreach (Kriteria::with('subKriterias')->get() as $kriteria) {
            $siswa->penilaians()->create(['kriteria_id' => $kriteria->id, 'sub_kriteria_id' => $kriteria->subKriterias->first()->id]);
        }
        $this->get('/dashboard')->assertViewHas('jumlahPenilaian', 1);
    }

    public function test_class_rename_updates_students_and_used_master_data_cannot_be_deleted(): void
    {
        $siswa = $this->student();
        $kelas = Kelas::where('nama', '1')->firstOrFail();
        $this->actingAs($this->kepala())->put("/kelas/{$kelas->id}", ['nama' => '2a'])->assertSessionHasErrors('nama');
        $this->put("/kelas/{$kelas->id}", ['nama' => '1a'])->assertSessionHasNoErrors();
        $this->assertSame('1A', $siswa->fresh()->kelas);
        $this->delete("/kelas/{$kelas->id}")->assertSessionHas('error');
        $this->assertModelExists($kelas);
        $tahun = TahunAjaran::create(['nama' => '2026/2027', 'tahun_mulai' => 2026, 'tahun_selesai' => 2027]);
        $this->delete("/tahun-ajaran/{$tahun->id}")->assertSessionHas('error');
        $this->assertModelExists($tahun);
    }

    public function test_dummy_reset_command_cannot_delete_production_data(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        $this->artisan('import:dummy')->assertFailed();
        $this->assertDatabaseCount('users', 2);
    }
}
