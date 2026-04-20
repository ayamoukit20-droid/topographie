<?php

namespace App\Policies;

use App\Models\Dossier;
use App\Models\User;

class DossierPolicy
{
    public function view(User $user, Dossier $dossier): bool
    {
        return $user->id === $dossier->user_id;
    }

    public function update(User $user, Dossier $dossier): bool
    {
        return $user->id === $dossier->user_id;
    }

    public function delete(User $user, Dossier $dossier): bool
    {
        return $user->id === $dossier->user_id;
    }
}
