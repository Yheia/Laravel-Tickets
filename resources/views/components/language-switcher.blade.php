<div class="flex items-center gap-1 px-2">
    @foreach(config('app.supported_locales') as $locale)
        
            href="{{ route('lang.switch', $locale) }}"
            class="text-sm font-semibold px-2 py-1 rounded transition-colors
                {{ app()->getLocale() === $locale
                    ? 'bg-primary-500 text-white'
                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}"
        >
            {{ $locale === 'en' ? '🇬🇧 EN' : '🇸🇦 AR' }}
        </a>
    @endforeach
</div>