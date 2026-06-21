<?php

namespace App\Policies;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SiswaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['wali_kelas', 'tata_usaha']);
    }

    public function view(User $user, Siswa $siswa): bool
    {
        if ($user->role === 'tata_usaha') return true;
        return $user->role === 'wali_kelas' && $siswa->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'wali_kelas';
    }

    public function update(User $user, Siswa $siswa): bool
    {
        if ($user->role === 'tata_usaha') return true;
        return $user->role === 'wali_kelas' && $siswa->user_id === $user->id;
    }

    public function delete(User $user, Siswa $siswa): bool
    {
        if ($user->role === 'tata_usaha') return true;
        return $user->role === 'wali_kelas' && $siswa->user_id === $user->id;
    }

    public function restore(User $user, Siswa $siswa): bool
    {
        return $user->role === 'tata_usaha';
    }

    public function forceDelete(User $user, Siswa $siswa): bool
    {
        return false;
    }
}
