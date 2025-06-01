
<?php



use App\Models\Booking;      // Your Booking model
use App\Policies\BookingPolicy; // Your BookingPolicy
use Illuminate\Support\Facades\Gate; // Import Gate
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
        // Register your policies here
        Gate::policy(Booking::class, BookingPolicy::class);
        // Gate::policy(AnotherModel::class, AnotherModelPolicy::class);
    }
}