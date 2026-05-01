<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Auth\Access\AuthorizationException;

class ApiController extends Controller
{
    use ApiResponses;

    protected $policyClass;

    public function __construct()
    {
        // Gate::guessPolicyNamesUsing(function () {
        //     return $this->policyClass;
        // });
    }

    public function include(string|array $relationships): bool
    {
        $param = request()->get('include');

        if (! isset($param)) {
            return false;
        }

        $includeValues = explode(',', strtolower($param));

        if (is_array($relationships)) {
            foreach ($relationships as $relationship) {
                if (in_array(strtolower($relationship), $includeValues)) {
                    return true;
                }
            }

            return false;
        }

        return in_array(strtolower($relationships), $includeValues);
    }

    public function isAble($ability, $targetModel)
    {
        try {
            $this->authorize($ability, [$targetModel, $this->policyClass]);

            return true;

            // $gate = Gate::policy($targetModel::class, $this->policyClass);
            // return $gate->authorize($ability, [$targetModel]);
        } catch (AuthorizationException $ex) {
            return false;
        }
    }
}
