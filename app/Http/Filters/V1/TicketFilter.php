<?php

namespace App\Http\Filters\V1;

use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TicketFilter extends QueryFilter
{
    protected $sortable = [
        'title',
        'status',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    public function createdAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $value);
    }

    public function include($value)
    {
        return $this->builder->with($value);
    }

    // $this->include('user.profile'); // string
    // $this->include('author, user.profile'); // comma-separated string
    // $this->include(['author', 'user.profile']); // array
    /*    public function include1($relations, bool $strict = false)
        {
            // Normalize input into array
            if (is_string($relations)) {
                $relations = explode(',', $relations);
            }

            $relations = array_map('trim', $relations); // remove any extra spaces
            $model = $this->builder->getModel();
            $validIncludes = [];
            $invalidIncludes = [];

            foreach ($relations as $relationPath) {
                $segments = explode('.', $relationPath);
                $currentModel = $model;
                $validPath = [];

                foreach ($segments as $segment) {
                    if (!method_exists($currentModel, $segment)) {
                        $invalidIncludes[] = $relationPath;
                        break;
                    }

                    $relation = $currentModel->$segment();

                    if (!($relation instanceof \Illuminate\Database\Eloquent\Relations\Relation)) {
                        $invalidIncludes[] = $relationPath;
                        break;
                    }

                    $validPath[] = $segment;
                    $currentModel = $relation->getRelated();
                }

                if (count($validPath) === count($segments)) {
                    $validIncludes[] = implode('.', $validPath);
                }
            }

            // Log invalids
            if (!empty($invalidIncludes)) {
                Log::warning('Invalid Eloquent relationships passed to include():', $invalidIncludes);

                // Throw exception if strict mode is enabled
                if ($strict) {
                    throw ValidationException::withMessages([
                        'includes' => ['Invalid relationship(s): ' . implode(', ', $invalidIncludes)],
                    ]);
                }
            }

            // Return builder with valid includes
            return !empty($validIncludes)
                ? $this->builder->with($validIncludes)
                : $this->builder;
        }
    */

    public function status($value)
    {
        return $this->builder->whereIn('status', explode(',', $value));
    }

    public function title($value)
    {
        $likeStr = str_replace('*', '%', $value);

        return $this->builder->where('title', 'like', $likeStr);
    }

    public function updatedAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $value);
    }
}
