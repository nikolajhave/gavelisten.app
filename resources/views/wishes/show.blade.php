<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Wish Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('wishes.edit', $wish) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Edit') }}
                </a>
                <form action="{{ route('wishes.destroy', $wish) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this wish?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Delete') }}
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold mb-4">{{ $wish->name }}</h3>
                        
                        @if ($wish->price)
                            <div class="mb-4">
                                <h4 class="text-lg font-semibold mb-1">{{ __('Price') }}</h4>
                                <p>${{ number_format($wish->price, 2) }}</p>
                            </div>
                        @endif
                        
                        @if ($wish->description)
                            <div class="mb-4">
                                <h4 class="text-lg font-semibold mb-1">{{ __('Description') }}</h4>
                                <p class="whitespace-pre-line">{{ $wish->description }}</p>
                            </div>
                        @endif
                        
                        @if ($wish->link)
                            <div class="mb-4">
                                <h4 class="text-lg font-semibold mb-1">{{ __('Link') }}</h4>
                                <a href="{{ $wish->link }}" target="_blank" class="text-blue-500 hover:text-blue-700 underline">
                                    {{ $wish->link }}
                                </a>
                            </div>
                        @endif
                        
                        <div class="mb-4">
                            <h4 class="text-lg font-semibold mb-1">{{ __('Created') }}</h4>
                            <p>{{ $wish->created_at->format('F j, Y, g:i a') }}</p>
                        </div>
                        
                        @if ($wish->created_at != $wish->updated_at)
                            <div class="mb-4">
                                <h4 class="text-lg font-semibold mb-1">{{ __('Last Updated') }}</h4>
                                <p>{{ $wish->updated_at->format('F j, Y, g:i a') }}</p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-6">
                        <a href="{{ route('wishes.index') }}" class="text-blue-500 hover:text-blue-700">
                            &larr; {{ __('Back to Wishes') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
