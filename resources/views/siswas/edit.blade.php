<x-app-layout>
    <x-slot name="header">
        Edit Data Siswa
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200 max-w-5xl mx-auto mb-10">
        <div class="p-6 md:p-8 text-slate-900">
            <div class="flex justify-between items-center mb-8 pb-4 border-b border-slate-100">
                <h3 class="text-2xl font-extrabold text-slate-800 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Data Siswa ({{ $siswa->kode_siswa }})
                </h3>
                <a href="{{ route('siswas.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>

            <form action="{{ route('siswas.update', $siswa->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Data Identitas -->
                    <div class="lg:col-span-5">
                        <div class="bg-slate-50 p-6 rounded-xl border border-slate-100 sticky top-6">
                            <h4 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 p-1.5 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </span>
                                Data Identitas
                            </h4>
                            
                            <div class="space-y-5">
                                <div>
                                    <label for="nisn" class="block mb-2 text-sm font-bold text-slate-700">NISN</label>
                                    <input type="text" id="nisn" name="nisn" value="{{ old('nisn', $siswa->nisn) }}" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 shadow-sm transition-all" required>
                                    @error('nisn') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="nama_siswa" class="block mb-2 text-sm font-bold text-slate-700">Nama Siswa</label>
                                    <input type="text" id="nama_siswa" name="nama_siswa" value="{{ old('nama_siswa', $siswa->nama_siswa) }}" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 shadow-sm transition-all" required>
                                    @error('nama_siswa') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="kelas" class="block mb-2 text-sm font-bold text-slate-700">Kelas</label>
                                    <input type="text" id="kelas" name="kelas" value="{{ old('kelas', $siswa->kelas) }}" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 shadow-sm transition-all" required>
                                    @error('kelas') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="alamat" class="block mb-2 text-sm font-bold text-slate-700">Alamat Lengkap</label>
                                    <textarea id="alamat" name="alamat" rows="3" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 shadow-sm transition-all">{{ old('alamat', $siswa->alamat) }}</textarea>
                                    @error('alamat') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="status_data" class="block mb-2 text-sm font-bold text-slate-700">Status Data</label>
                                    <select id="status_data" name="status_data" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 shadow-sm transition-all font-medium" required>
                                        <option value="draft" {{ old('status_data', $siswa->status_data) == 'draft' ? 'selected' : '' }}>Draft (Belum lengkap)</option>
                                        <option value="submitted" {{ old('status_data', $siswa->status_data) == 'submitted' ? 'selected' : '' }}>Submitted (Siap diproses TOPSIS)</option>
                                    </select>
                                    @error('status_data') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Penilaian -->
                    <div class="lg:col-span-7">
                        <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm">
                            <h4 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                                <span class="bg-yellow-100 text-yellow-600 p-1.5 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </span>
                                Data Penilaian / Kriteria
                            </h4>
                            
                            <div class="space-y-8">
                                <!-- C1 -->
                                <div>
                                    <label class="block mb-3 text-sm font-bold text-slate-700">Pekerjaan Ayah (C1)</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @foreach($c1 as $opt)
                                            <label class="cursor-pointer relative">
                                                <input type="radio" name="c1_id" value="{{ $opt->id }}" class="peer sr-only" {{ old('c1_id', $siswa->c1_id) == $opt->id ? 'checked' : '' }} required>
                                                <div class="rounded-xl border-2 border-slate-200 bg-white p-4 hover:border-blue-300 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all text-sm flex flex-col items-center justify-center min-h-[4rem] text-center">
                                                    <span class="block font-bold text-slate-700 peer-checked:text-blue-800">{{ $opt->nama_sub_kriteria }}</span>
                                                    <span class="block text-xs mt-1 text-slate-500 peer-checked:text-blue-600 font-medium">Nilai: {{ $opt->nilai }}</span>
                                                </div>
                                                <div class="absolute top-2 right-2 text-blue-600 hidden peer-checked:block">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('c1_id') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>

                                <!-- C2 -->
                                <div>
                                    <label class="block mb-3 text-sm font-bold text-slate-700">Penghasilan Ayah (C2)</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @foreach($c2 as $opt)
                                            <label class="cursor-pointer relative">
                                                <input type="radio" name="c2_id" value="{{ $opt->id }}" class="peer sr-only" {{ old('c2_id', $siswa->c2_id) == $opt->id ? 'checked' : '' }} required>
                                                <div class="rounded-xl border-2 border-slate-200 bg-white p-4 hover:border-blue-300 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all text-sm flex flex-col items-center justify-center min-h-[4rem] text-center">
                                                    <span class="block font-bold text-slate-700 peer-checked:text-blue-800">{{ $opt->nama_sub_kriteria }}</span>
                                                    <span class="block text-xs mt-1 text-slate-500 peer-checked:text-blue-600 font-medium">Nilai: {{ $opt->nilai }}</span>
                                                </div>
                                                <div class="absolute top-2 right-2 text-blue-600 hidden peer-checked:block">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('c2_id') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>

                                <!-- C3 -->
                                <div>
                                    <label class="block mb-3 text-sm font-bold text-slate-700">Pekerjaan Ibu (C3)</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @foreach($c3 as $opt)
                                            <label class="cursor-pointer relative">
                                                <input type="radio" name="c3_id" value="{{ $opt->id }}" class="peer sr-only" {{ old('c3_id', $siswa->c3_id) == $opt->id ? 'checked' : '' }} required>
                                                <div class="rounded-xl border-2 border-slate-200 bg-white p-4 hover:border-blue-300 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all text-sm flex flex-col items-center justify-center min-h-[4rem] text-center">
                                                    <span class="block font-bold text-slate-700 peer-checked:text-blue-800">{{ $opt->nama_sub_kriteria }}</span>
                                                    <span class="block text-xs mt-1 text-slate-500 peer-checked:text-blue-600 font-medium">Nilai: {{ $opt->nilai }}</span>
                                                </div>
                                                <div class="absolute top-2 right-2 text-blue-600 hidden peer-checked:block">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('c3_id') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>

                                <!-- C4 -->
                                <div>
                                    <label class="block mb-3 text-sm font-bold text-slate-700">Penghasilan Ibu (C4)</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @foreach($c4 as $opt)
                                            <label class="cursor-pointer relative">
                                                <input type="radio" name="c4_id" value="{{ $opt->id }}" class="peer sr-only" {{ old('c4_id', $siswa->c4_id) == $opt->id ? 'checked' : '' }} required>
                                                <div class="rounded-xl border-2 border-slate-200 bg-white p-4 hover:border-blue-300 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all text-sm flex flex-col items-center justify-center min-h-[4rem] text-center">
                                                    <span class="block font-bold text-slate-700 peer-checked:text-blue-800">{{ $opt->nama_sub_kriteria }}</span>
                                                    <span class="block text-xs mt-1 text-slate-500 peer-checked:text-blue-600 font-medium">Nilai: {{ $opt->nilai }}</span>
                                                </div>
                                                <div class="absolute top-2 right-2 text-blue-600 hidden peer-checked:block">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('c4_id') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>

                                <!-- C5 -->
                                <div>
                                    <label class="block mb-3 text-sm font-bold text-slate-700">Jumlah Tanggungan Keluarga (C5)</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @foreach($c5 as $opt)
                                            <label class="cursor-pointer relative">
                                                <input type="radio" name="c5_id" value="{{ $opt->id }}" class="peer sr-only" {{ old('c5_id', $siswa->c5_id) == $opt->id ? 'checked' : '' }} required>
                                                <div class="rounded-xl border-2 border-slate-200 bg-white p-4 hover:border-blue-300 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all text-sm flex flex-col items-center justify-center min-h-[4rem] text-center">
                                                    <span class="block font-bold text-slate-700 peer-checked:text-blue-800">{{ $opt->nama_sub_kriteria }}</span>
                                                    <span class="block text-xs mt-1 text-slate-500 peer-checked:text-blue-600 font-medium">Nilai: {{ $opt->nilai }}</span>
                                                </div>
                                                <div class="absolute top-2 right-2 text-blue-600 hidden peer-checked:block">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('c5_id') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>

                                <!-- C6 -->
                                <div>
                                    <label class="block mb-3 text-sm font-bold text-slate-700">Status Siswa (C6)</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @foreach($c6 as $opt)
                                            <label class="cursor-pointer relative">
                                                <input type="radio" name="c6_id" value="{{ $opt->id }}" class="peer sr-only" {{ old('c6_id', $siswa->c6_id) == $opt->id ? 'checked' : '' }} required>
                                                <div class="rounded-xl border-2 border-slate-200 bg-white p-4 hover:border-blue-300 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all text-sm flex flex-col items-center justify-center min-h-[4rem] text-center">
                                                    <span class="block font-bold text-slate-700 peer-checked:text-blue-800">{{ $opt->nama_sub_kriteria }}</span>
                                                    <span class="block text-xs mt-1 text-slate-500 peer-checked:text-blue-600 font-medium">Nilai: {{ $opt->nilai }}</span>
                                                </div>
                                                <div class="absolute top-2 right-2 text-blue-600 hidden peer-checked:block">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('c6_id') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 border-t border-slate-200 pt-6 flex justify-end">
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-lg px-8 py-3.5 text-center flex items-center gap-2 shadow-md hover:shadow-lg transition-all transform active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Simpan Perubahan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
