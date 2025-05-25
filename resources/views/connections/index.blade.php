<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Connections') }}
            </h2>
            <a href="{{ route('connections.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Connect with Users') }}
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

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Connection Requests') }}</h3>
                    
                    @if ($receivedConnections->where('status', 'pending')->count() > 0)
                        <div class="space-y-4">
                            @foreach ($receivedConnections->where('status', 'pending') as $connection)
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-semibold">{{ $connection->user->name }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $connection->user->email }}</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <form action="{{ route('connections.accept', $connection->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                {{ __('Accept') }}
                                            </button>
                                        </form>
                                        <form action="{{ route('connections.reject', $connection->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                {{ __('Reject') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>{{ __('No pending connection requests.') }}</p>
                    @endif
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">{{ __('My Connections') }}</h3>
                    
                    @if ($connections->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($connections as $connection)
                                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <p class="font-semibold">{{ $connection->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $connection->email }}</p>
                                    <div class="mt-3 flex justify-between">
                                        <a href="{{ route('connections.show', $connection->id) }}" class="text-blue-500 hover:text-blue-700">
                                            {{ __('View Wishes') }}
                                        </a>
                                        
                                        @php
                                            $connectionRecord = Auth::user()->sentConnections()->where('connected_user_id', $connection->id)->first() 
                                                ?? Auth::user()->receivedConnections()->where('user_id', $connection->id)->first();
                                        @endphp
                                        
                                        @if ($connectionRecord)
                                            <form action="{{ route('connections.destroy', $connectionRecord->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this connection?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700">
                                                    {{ __('Remove') }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>{{ __('You have no connections yet. Connect with other users to see their wishes.') }}</p>
                    @endif
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Sent Connection Requests') }}</h3>
                    
                    @if ($sentConnections->where('status', 'pending')->count() > 0)
                        <div class="space-y-4">
                            @foreach ($sentConnections->where('status', 'pending') as $connection)
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-semibold">{{ $connection->connectedUser->name }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $connection->connectedUser->email }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('Status') }}: {{ __('Pending') }}</p>
                                    </div>
                                    <form action="{{ route('connections.destroy', $connection->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this connection request?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            {{ __('Cancel') }}
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>{{ __('No pending sent connection requests.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
