<?php

namespace App\Policies;

use App\Models\Band;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Model $model = null): bool
    {
        // Caso precisemos de permitir criar algum modelo, o parâmetro aqui já está acrescentado com null por defeito. Acrescentamos o argumento separado por vírula no middleware da route em web.php, e definimos aqui a lógica
        // Por enquanto vamos só dizer que um utilizador que não seja admin não pode criar nenhum modelo.
        // Retornar false se user_type não for admin (0)

        return $user->user_type === 0;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        //
    }
}
