<?php

namespace App\Providers;

use App\Modules\Admin\Repositories\GroupRepository;
use App\Modules\Admin\Repositories\Interfaces\IGroupRepository;
use App\Modules\Admin\Repositories\Interfaces\IManagementDataRepository;
use App\Modules\Admin\Repositories\ManagementDataRepository;
use App\Modules\Admin\Services\GroupService;
use App\Modules\Admin\Services\Interfaces\IGroupService;
use App\Modules\Admin\Services\Interfaces\IManagementDataService;
use App\Modules\Admin\Services\ManagementDataService;
use App\Modules\Coaches\Repositories\CriteriaRepository;
use App\Modules\Coaches\Repositories\CriteriaWeightRepository;
use App\Modules\Coaches\Repositories\EvaluationReportRepository;
use App\Modules\Coaches\Repositories\EvaluationRepository;
use App\Modules\Coaches\Repositories\Interfaces\ICriteriaRepository;
use App\Modules\Coaches\Repositories\Interfaces\ICriteriaWeightRepository;
use App\Modules\Coaches\Repositories\Interfaces\IEvaluationReportRepository;
use App\Modules\Coaches\Repositories\Interfaces\IEvaluationRepository;
use App\Modules\Coaches\Repositories\Interfaces\IPairwiseCriteriaRepository;
use App\Modules\Coaches\Repositories\Interfaces\IPairwiseSubCriteriaRepository;
use App\Modules\Coaches\Repositories\Interfaces\IPlayerScoreMappingRepository;
use App\Modules\Coaches\Repositories\Interfaces\IPositionRepository;
use App\Modules\Coaches\Repositories\Interfaces\ISubCriteriaWeightRepository;
use App\Modules\Coaches\Repositories\PairwiseCriteriaRepository;
use App\Modules\Coaches\Repositories\PairwiseSubCriteriaRepository;
use App\Modules\Coaches\Repositories\PlayerScoreMappingRepository;
use App\Modules\Coaches\Repositories\PositionRepository;
use App\Modules\Coaches\Repositories\SubCriteriaWeightRepository;
use App\Modules\Coaches\Services\AhpCalculationService;
use App\Modules\Coaches\Services\CriteriaService;
use App\Modules\Coaches\Services\EvaluationReportService;
use App\Modules\Coaches\Services\EvaluationService;
use App\Modules\Coaches\Services\Interfaces\IAhpCalculationService;
use App\Modules\Coaches\Services\Interfaces\ICriteriaService;
use App\Modules\Coaches\Services\Interfaces\IEvaluationReportService;
use App\Modules\Coaches\Services\Interfaces\IEvaluationService;
use App\Modules\Coaches\Services\Interfaces\IPairwiseCriteriaService;
use App\Modules\Coaches\Services\Interfaces\IPairwiseSubCriteriaService;
use App\Modules\Coaches\Services\Interfaces\IPlayerScoreMappingService;
use App\Modules\Coaches\Services\Interfaces\IPositionService;
use App\Modules\Coaches\Services\PairwiseCriteriaService;
use App\Modules\Coaches\Services\PairwiseSubCriteriaService;
use App\Modules\Coaches\Services\PlayerScoreMappingService;
use App\Modules\Coaches\Services\PositionService;
use App\Modules\User\Repositories\Interfaces\IUserRepository;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\User\Services\Interfaces\IUserService;
use App\Modules\User\Services\UserService;
use Illuminate\Support\ServiceProvider;

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

        $this->app->bind(IManagementDataRepository::class, ManagementDataRepository::class);
        $this->app->bind(IManagementDataService::class, ManagementDataService::class);

        $this->app->bind(ICriteriaService::class, CriteriaService::class);
        $this->app->bind(ICriteriaRepository::class, CriteriaRepository::class);

        $this->app->bind(IEvaluationRepository::class, EvaluationRepository::class);
        $this->app->bind(IEvaluationService::class, EvaluationService::class);

        $this->app->bind(IPositionRepository::class, PositionRepository::class);
        $this->app->bind(IPositionService::class, PositionService::class);

        $this->app->bind(ICriteriaWeightRepository::class, CriteriaWeightRepository::class);
        $this->app->bind(ISubCriteriaWeightRepository::class, SubCriteriaWeightRepository::class);

        $this->app->bind(IPairwiseSubCriteriaRepository::class, PairwiseSubCriteriaRepository::class);
        $this->app->bind(IPairwiseSubCriteriaService::class, PairwiseSubCriteriaService::class);

        $this->app->bind(IPairwiseCriteriaRepository::class, PairwiseCriteriaRepository::class);
        $this->app->bind(IPairwiseCriteriaService::class, PairwiseCriteriaService::class);

        $this->app->bind(IAhpCalculationService::class, AhpCalculationService::class);
        $this->app->bind(IPlayerScoreMappingRepository::class, PlayerScoreMappingRepository::class);
        $this->app->bind(IPlayerScoreMappingService::class, PlayerScoreMappingService::class);

        $this->app->bind(IEvaluationReportRepository::class, EvaluationReportRepository::class);
        $this->app->bind(IEvaluationReportService::class, EvaluationReportService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
