<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Wishes') }}
            </h2>
            <a href="{{ route('wishes.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Add New Wish') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

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
                                        <div class="flex justify-between mt-4">
                                            <a href="{{ route('wishes.show', $wish) }}" class="text-blue-500 hover:text-blue-700">{{ __('View') }}</a>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('wishes.edit', $wish) }}" class="text-yellow-500 hover:text-yellow-700">{{ __('Edit') }}</a>
                                                <form action="{{ route('wishes.destroy', $wish) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this wish?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700">{{ __('Delete') }}</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>{{ __('You have no wishes yet. Create one by clicking the "Add New Wish" button.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
