<?php

namespace App\Policies;

use App\Models\Recruitment;
use App\Models\User;

class RecruitmentPolicy
{
    public function view(User $user, Recruitment $recruitment): bool
    {
        if ($user->isSuperadmin()) return true;

        return $user->ormawas->contains('id', $recruitment->ormawa_id);
    }

    public function update(User $user, Recruitment $recruitment): bool
    {
        return $this->view($user, $recruitment);
    }

    public function delete(User $user, Recruitment $recruitment): bool
    {
        return $this->view($user, $recruitment);
    }
}