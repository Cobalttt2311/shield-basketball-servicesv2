<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Modules\User\Services\Interfaces\IUserService;
use App\Modules\User\Services\UserService;

use App\Modules\User\Repositories\Interfaces\IUserRepository;
use App\Modules\User\Repositories\UserRepository;

use App\Modules\Admin\Repositories\Interfaces\IGroupRepository;
use App\Modules\Admin\Repositories\GroupRepository;
use App\Modules\Admin\Services\Interfaces\IGroupService;
use App\Modules\Admin\Services\GroupService;

use App\Modules\Admin\Repositories\Interfaces\IManagementDataRepository;
use App\Modules\Admin\Repositories\ManagementDataRepository;
use App\Modules\Admin\Services\Interfaces\IManagementDataService;
use App\Modules\Admin\Services\ManagementDataService;

use App\Modules\Coaches\Services\Interfaces\ICriteriaService;
use App\Modules\Coaches\Services\CriteriaService;
use App\Modules\Coaches\Repositories\Interfaces\ICriteriaRepository;
use App\Modules\Coaches\Repositories\CriteriaRepository;

use App\Modules\Coaches\Repositories\Interfaces\IEvaluationRepository;
use App\Modules\Coaches\Repositories\EvaluationRepository;
use App\Modules\Coaches\Services\Interfaces\IEvaluationService;
use App\Modules\Coaches\Services\EvaluationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);

        $this->app->bind(IGroupRepository::class, GroupRepository::class);
        $this->app->bind(IGroupService::class, GroupService::class);

        $this->app->bind(IManagementDataRepository::class,ManagementDataRepository::class);
        $this->app->bind(IManagementDataService::class,ManagementDataService::class);

        $this->app->bind(ICriteriaService::class, CriteriaService::class);
        $this->app->bind(ICriteriaRepository::class, CriteriaRepository::class);

        $this->app->bind(IEvaluationRepository::class, EvaluationRepository::class);
        $this->app->bind(IEvaluationService::class, EvaluationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}