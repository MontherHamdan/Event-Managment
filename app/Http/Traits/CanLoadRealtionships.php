<?php

namespace App\Http\Traits;

use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait CanLoadRealtionships
{

    public function loadRelationships(
        Model|QueryBuilder|EloquentBuilder|HasMany $for,
        ?array $relations = null
    ): Model|QueryBuilder|EloquentBuilder|HasMany {

        // ?? : this start from the left if its empty take the seconed arguement
        $relations = $relations ?? $this->relations ?? [];

        foreach ($relations as $relation) {
            // when(): when the first argument true it will run the seconed functioin which can alter the query
            $for->when(
                $this->shouldIncludeRelation($relation),
                fn ($q) => $for instanceof Model ? $for->load($relation) : $q->with($relation)
            );
        }

        return $for;
    }

    // optional relation loaded
    // this method should tell us if specific realtionship should be included or not
    protected function shouldIncludeRelation(string $relation): bool
    {
        $include = request()->query('include');

        if (!$include) {
            return false;
        }

        $relations = array_map('trim', explode(',', $include));

        return in_array($relation, $relations);
    }
}
