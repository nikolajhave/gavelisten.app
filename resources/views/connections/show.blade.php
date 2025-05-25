<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __(':name\'s Wishes', ['name' => $user->name]) }}
            </h2>
            <a href="{{ route('connections.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Back to Connections') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($wishes->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($wishes as $wish)
                                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden">
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold mb-2">{{ $wish->name }}</h3>
                                        @if ($wish->price)
                                            <p class="text-gray-600 dark:text-gray-300 mb-2">{{ __('Price') }}: ${{ number_format($wish->price, 2) }}</p>
                                        @endif
                                        @if ($wish->description)
                                            <p class="text-gray-600 dark:text-gray-300 mb-2">{{ Str::limit($wish->description, 100) }}</p>
                                        @endif
                                        @if ($wish->link)
                                            <a href="{{ $wish->link }}" target="_blank" class="text-blue-500 hover:text-blue-700 block mt-2">
                                                {{ __('View Item') }} →
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>{{ __(':name has no wishes yet.', ['name' => $user->name]) }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
