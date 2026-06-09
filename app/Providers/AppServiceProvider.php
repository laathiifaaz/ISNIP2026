<?php

namespace App\Providers;

use App\Models\AnalysisJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            \URL::forceScheme('https');
        }
        
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            \URL::forceRootUrl('https://' . $_SERVER['HTTP_X_FORWARDED_HOST']);
        }

        View::composer('layouts.layout', function ($view): void {
            $notificationJobs = collect();

            if (Auth::check()) {
                AnalysisJob::where('user_id', Auth::id())
                    ->whereIn('status', ['SUBMITTED', 'RUNNING'])
                    ->latest('submit_date')
                    ->get()
                    ->each(fn (AnalysisJob $job) => $job->checkSimulationStatus());

                $notificationJobs = AnalysisJob::where('user_id', Auth::id())
                    ->latest('updated_at')
                    ->take(5)
                    ->get();
            }

            $view->with('notificationJobs', $notificationJobs);
        });
    }
}
