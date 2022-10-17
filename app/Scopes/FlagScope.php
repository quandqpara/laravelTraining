<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class FlagScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder
            ->when($model->getTable() == 'teams', function ($q) {
                $q->where('teams.del_flag','=', config('global.DEL_FLAG_OFF'));
            })
            ->when($model->getTable() == 'employees', function ($q) {
                $q->where('employees.del_flag','=', config('global.DEL_FLAG_OFF'));
            });
    }
}
