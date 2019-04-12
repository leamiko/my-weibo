<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // 定义个update策略,策略跟model有关
    public function update(User $currentUser, User $user) {
        return $currentUser->id === $user->id; // 如果是当前用户，则支持update策略
    }
}
