<?php

namespace Tests\Feature;

use App\Exports\SiswaTemplateExport;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
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

    public function test_guest_cannot_access_import_page()
    {
        $response = $this->get('/siswa/import');

        $response->assertRedirect('/login');
    }

    public function test_kepala_sekolah_cannot_access_import_page()
    {
        $user = User::factory()->create(['role' => 'kepala_sekolah']);

        $response = $this->actingAs($user)->get('/siswa/import');

        $response->assertStatus(403);
    }

    private function previewCsv(string $rows)
    {
        Storage::fake('local');
        $this->actingAs(User::factory()->create(['role' => 'wali_kelas']));
        $file = UploadedFile::fake()->createWithContent('siswas.csv', "NISN,Nama Siswa,Kelas,Alamat,Tahun Ajaran\n".$rows);
        $upload = $this->post('/siswa/import/sheets', ['file' => $file])->assertOk();

        return $this->post('/siswa/import/preview', [
            'temp_path' => $upload->json('path'), 'sheet_index' => 0,
        ]);
    }

    public function test_csv_import_preserves_nisn_and_creates_students_without_assessments(): void
    {
        $this->previewCsv("0012345678,Siswa CSV,1,Bandung,2026/2027\n")
            ->assertOk()->assertViewHas('summary', fn ($summary) => $summary['can_import']);
        $path = session('temp_file_path');
        $this->post('/siswa/import/process')->assertRedirect(route('siswas.index'))->assertSessionHas('success');
        $this->assertDatabaseHas('siswas', ['nisn' => '0012345678', 'nama_siswa' => 'Siswa CSV', 'periode' => '2026/2027']);
        $this->assertDatabaseCount('penilaian_siswas', 0);
        Storage::assertMissing($path);
    }

    public function test_required_fields_and_duplicate_nisn_in_file_block_entire_import(): void
    {
        foreach ([",Tanpa NISN,1,Bandung,2026/2027\n", "123,Siswa,1,Bandung,\n",
            "123,Siswa A,1,Bandung,2026/2027\n123,Siswa B,1,Bandung,2026/2027\n"] as $rows) {
            $this->previewCsv($rows)->assertOk()->assertViewHas('summary', fn ($summary) => ! $summary['can_import']);
            $this->post('/siswa/import/process')->assertSessionHas('error');
            $this->assertDatabaseCount('siswas', 0);
        }
    }

    public function test_preview_cannot_read_another_upload_or_arbitrary_path(): void
    {
        $this->previewCsv("123,Siswa,1,Bandung,2026/2027\n")->assertOk();
        $this->post('/siswa/import/preview', ['temp_path' => '../.env', 'sheet_index' => 0])
            ->assertSessionHasErrors('temp_path');
        $this->post('/siswa/import/preview', ['temp_path' => session('temp_file_path'), 'sheet_index' => -1])
            ->assertSessionHasErrors('sheet_index');
    }

    public function test_import_revalidates_nisn_after_preview_and_rolls_back_all_rows(): void
    {
        $this->previewCsv("123,Siswa A,1,Bandung,2026/2027\n456,Siswa B,1,Bandung,2026/2027\n")->assertOk();
        Siswa::create(['user_id' => auth()->id(), 'kode_siswa' => 'SW-0001', 'nisn' => '456',
            'nama_siswa' => 'Sudah Ada', 'kelas' => '1', 'periode' => '2026/2027', 'status_data' => 'draft']);
        $this->post('/siswa/import/process')->assertSessionHas('error');
        $this->assertDatabaseCount('siswas', 1);
        $this->assertDatabaseMissing('siswas', ['nisn' => '123']);
    }

    public function test_templates_can_be_downloaded(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'wali_kelas']));
        foreach (['csv', 'xlsx'] as $type) {
            $this->get('/siswa/import/template/'.$type)->assertOk()->assertDownload('Template_Import_Siswa.'.$type);
        }
        $this->get('/siswa/import/template/php')->assertNotFound();
    }

    public function test_xlsx_template_can_be_uploaded_previewed_and_imported(): void
    {
        Storage::fake('local');
        $this->actingAs(User::factory()->create());
        $content = Excel::raw(new SiswaTemplateExport, \Maatwebsite\Excel\Excel::XLSX);
        $file = UploadedFile::fake()->createWithContent('template.xlsx', $content);
        $upload = $this->post('/siswa/import/sheets', ['file' => $file])
            ->assertOk()->assertJsonPath('sheets.0', 'Template Data');
        $this->post('/siswa/import/preview', ['temp_path' => $upload->json('path'), 'sheet_index' => 0])
            ->assertOk()->assertViewHas('summary', fn ($summary) => $summary['can_import']);
        $this->post('/siswa/import/process')->assertSessionHas('success');
        $this->assertDatabaseHas('siswas', ['nisn' => '1234567890']);
    }
}
