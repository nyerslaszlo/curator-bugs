<?php

namespace App\Http\Middleware;

use App\Traits\Model\BelongsToOrganization;
use App\Traits\Model\BelongsToTenant;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use ReflectionClass;

class ApplyTenantScopesMiddleware
{

	public function handle(Request $request, Closure $next)
	{
        $scope = fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant());

        //Get all the Models in the app, then check if they have the "BelongsToOrganization" trait. If they do, add the scope to them.

        $models = $this->getModels();
        foreach ($models as $model) {
            $reflection = new ReflectionClass($model);
            if (in_array(BelongsToTenant::class, $reflection->getTraitNames())) {
                $model::addGlobalScope($scope);
            }
        }

        return $next($request);
	}

    private function getModels(): Collection
    {
        $models = collect(File::allFiles(app_path()))
            ->map(function ($item) {
                $path = $item->getRelativePathName();

                return sprintf('\%s%s',
                    Container::getInstance()->getNamespace(),
                    str_replace('/', '\\', substr($path, 0, strrpos($path, '.'))));
            })
            ->filter(function ($class) {
                $valid = false;

                if (class_exists($class)) {
                    $reflection = new \ReflectionClass($class);
                    $valid      = $reflection->isSubclassOf(Model::class) &&
                        !$reflection->isAbstract();
                }

                return $valid;
            });

        return $models->values();
    }
}
