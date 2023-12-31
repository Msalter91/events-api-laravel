<?php 

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait CanLoadRelationships
{
  protected function shouldIncludeRelation(string $relation): bool
  {
    $include = request()->query('include');
    if(!$include) {
      return false;
    }
    $relations = array_map('trim', explode(',', $include));

    return in_array($relation, $relations);
  }

  public function LoadRelationships(
    Model | Builder | QueryBuilder $for,
    ?array $relations = null
  ):  Model | Builder | QueryBuilder
  {

    $relations = $relations ?? $this->relations ?? [];

    foreach($relations as $relation) {
      $for->when(
        $this->shouldIncludeRelation($relation), 
        fn($q) => $for instanceof Model ? $for->load($relation) : $q->with($relation)
      );
    };

    return $for;
  }
}