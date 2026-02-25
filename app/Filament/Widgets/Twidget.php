<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Ticket;
use App\Models\User;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class Twidget extends StatsOverviewWidget
{
    use InteractsWithPageFilters;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $start = $this->filters['startDate'] ?? null;
        $end = $this->filters['endDate'] ?? null;

        return [
           Stat::make('total_tickets', Ticket::query()
                ->when($start, fn ($query) => $query->whereDate('created_at', '>=', $start))
                ->when($end, fn ($query) => $query->whereDate('created_at', '<=', $end))
                ->count())
                ->description('All tickets in the system')
                ->icon('heroicon-o-ticket')
                ->color('primary'),


           Stat::make('open_tickets', Ticket::query()
                ->where('status', 'open')
                ->when($start, fn ($query) => $query->whereDate('created_at', '>=', $start))
                ->when($end, fn ($query) => $query->whereDate('created_at', '<=', $end))
                ->count())
                ->description('Open Tickets')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger'),


            Stat::make('in_progress_tickets', Ticket::query()
                ->where('status', 'in_progress')
                ->when($start, fn ($query) => $query->whereDate('created_at', '>=', $start))
                ->when($end, fn ($query) => $query->whereDate('created_at', '<=', $end))
                ->count())
                ->description('Tickets in Progress')
                ->icon('heroicon-o-cog')
                ->color('warning'),


           Stat::make('closed_tickets', Ticket::query()
                ->where('status', 'closed')
                ->when($start, fn ($query) => $query->whereDate('created_at', '>=', $start))
                ->when($end, fn ($query) => $query->whereDate('created_at', '<=', $end))
                ->count())
                ->description('Closed Tickets')
                ->icon('heroicon-o-check-circle')
                ->color('success'),


            Stat::make('Support Team', User::where('role', 'support')->count())
                ->description('Total Support Team Members')
                ->icon('heroicon-o-users')
                ->color('secondary'),

            Stat::make('Users', User::where('role', 'user')->count())
                ->description('Total Users')
                ->icon('heroicon-o-user')
                ->color('primary'),
        ];
    }
}
