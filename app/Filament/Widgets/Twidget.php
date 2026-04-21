<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use App\Models\Ticket;
use App\Models\User;

class Twidget extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected int|string|array $columnSpan = 'full';


    public static function canView(): bool
    {
    $user = auth()->user();

    return $user && (
        $user->isSupervisor() || $user->isSectoradmin() || $user->isSupport()
    );
    }

    protected function getStats(): array
    {
        $user = auth()->user();
        $start = $this->filters['startDate'] ?? null;
        $end = $this->filters['endDate'] ?? null;

       
        $ticketQuery = fn () => Ticket::query()
            ->when($start, fn ($q) => $q->whereDate('created_at', '>=', $start))
            ->when($end, fn ($q) => $q->whereDate('created_at', '<=', $end))
            ->when(!$user->isSupervisor(), fn ($q) => $q->where('sector', $user->sector));

        $stats = [];

        // Tickets stats 
        if ($user->isSupervisor() || $user->isSectoradmin() || $user->isSupport()) {
            $stats[] = Stat::make('Total Tickets', $ticketQuery()->count())
                ->description('All tickets in the system')
                ->icon('heroicon-o-ticket')
                ->color('primary');

            $stats[] = Stat::make('Open Tickets', $ticketQuery()->where('status','open')->count())
                ->description('Open tickets')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger');

            $stats[] = Stat::make('In Progress Tickets', $ticketQuery()->where('status','in_progress')->count())
                ->description('Tickets in progress')
                ->icon('heroicon-o-cog')
                ->color('warning');

            $stats[] = Stat::make('Closed Tickets', $ticketQuery()->where('status','closed')->count())
                ->description('Closed tickets')
                ->icon('heroicon-o-check-circle')
                ->color('success');
        }

        // Users/support stats
        if ($user->isSupervisor()) {
            $stats[] = Stat::make('Support Team', User::where('role','support')->count())
                ->description('Total support team members')
                ->icon('heroicon-o-users')
                ->color('secondary');

            $stats[] = Stat::make('Users', User::where('role','user')->count())
                ->description('Total users')
                ->icon('heroicon-o-user')
                ->color('primary');
                
        } elseif ($user->isSectoradmin() || $user->isSupport()) {
            // Only count users/support in their sector
            $stats[] = Stat::make('Support Team', User::where('role','support')->where('sector', $user->sector)->count())
                ->description('Support team members in your sector')
                ->icon('heroicon-o-users')
                ->color('secondary');

            $stats[] = Stat::make('Users', User::where('role','user')->where('sector', $user->sector)->count())
                ->description('Users in your sector')
                ->icon('heroicon-o-user')
                ->color('primary');
        }

        return $stats;
    }
}