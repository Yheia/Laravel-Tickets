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

        if ($user->isSupervisor() || $user->isSectoradmin() || $user->isSupport()) {
            $stats[] = Stat::make(__('Total Tickets'), $ticketQuery()->count())
                ->description(__('All tickets in the system'))
                ->icon('heroicon-o-ticket')
                ->color('primary');

            $stats[] = Stat::make(__('Open Tickets'), $ticketQuery()->where('status', 'open')->count())
                ->description(__('Open tickets'))
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger');

            $stats[] = Stat::make(__('In Progress Tickets'), $ticketQuery()->where('status', 'in_progress')->count())
                ->description(__('Tickets in progress'))
                ->icon('heroicon-o-cog')
                ->color('warning');

            $stats[] = Stat::make(__('Closed Tickets'), $ticketQuery()->where('status', 'closed')->count())
                ->description(__('Closed tickets'))
                ->icon('heroicon-o-check-circle')
                ->color('success');
        }

        if ($user->isSupervisor()) {
            $stats[] = Stat::make(__('Support Team'), User::where('role', 'support')->count())
                ->description(__('Total support team members'))
                ->icon('heroicon-o-users')
                ->color('secondary');

            $stats[] = Stat::make(__('Users'), User::where('role', 'user')->count())
                ->description(__('Total users'))
                ->icon('heroicon-o-user')
                ->color('primary');

        } elseif ($user->isSectoradmin() || $user->isSupport()) {
            $stats[] = Stat::make(__('Support Team'), User::where('role', 'support')->where('sector', $user->sector)->count())
                ->description(__('Support team members in your sector'))
                ->icon('heroicon-o-users')
                ->color('secondary');

            $stats[] = Stat::make(__('Users'), User::where('role', 'user')->where('sector', $user->sector)->count())
                ->description(__('Users in your sector'))
                ->icon('heroicon-o-user')
                ->color('primary');
        }

        return $stats;
    }
}