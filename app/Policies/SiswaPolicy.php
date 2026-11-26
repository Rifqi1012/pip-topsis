<?php

namespace App\Policies;

use App\Models\Siswa;
use App\Models\User;

class SiswaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['wali_kelas', 'kepala_sekolah']);
    }

    public function view(User $user, Siswa $siswa): bool
    {
        if ($user->role === 'kepala_sekolah') {
            return true;
        }

        return $user->role === 'wali_kelas' && $siswa->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'wali_kelas';
    }

    public function update(User $user, Siswa $siswa): bool
    {
        return $user->role === 'wali_kelas' && $siswa->user_id === $user->id;
    }

    public function delete(User $user, Siswa $siswa): bool
    {
        return $user->role === 'wali_kelas' && $siswa->user_id === $user->id;
    }

    public function restore(User $user, Siswa $siswa): bool
    {
        return $user->role === 'kepala_sekolah';
    }

    public function forceDelete(User $user, Siswa $siswa): bool
    {
        return false;
    }
}
