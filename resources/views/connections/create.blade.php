<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Connect with Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Available Users') }}</h3>
                    
                    @if ($users->count() > 0)
                        <div class="space-y-4">
                            @foreach ($users as $user)
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-semibold">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                                    </div>
                                    
                                    @php
                                        $sentConnection = Auth::user()->sentConnections()
                                            ->where('connected_user_id', $user->id)
                                            ->first();
                                        $receivedConnection = Auth::user()->receivedConnections()
                                            ->where('user_id', $user->id)
                                            ->first();
                                    @endphp
                                    
                                    @if ($sentConnection)
                                        @if ($sentConnection->status === 'pending')
                                            <span class="text-yellow-500">{{ __('Request Pending') }}</span>
                                        @elseif ($sentConnection->status === 'accepted')
                                            <span class="text-green-500">{{ __('Connected') }}</span>
                                        @else
                                            <span class="text-red-500">{{ __('Request Rejected') }}</span>
                                        @endif
                                    @elseif ($receivedConnection)
                                        @if ($receivedConnection->status === 'pending')
                                            <div class="flex space-x-2">
                                                <form action="{{ route('connections.accept', $receivedConnection->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                        {{ __('Accept') }}
                                                    </button>
                                                </form>
                                                <form action="{{ route('connections.reject', $receivedConnection->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                        {{ __('Reject') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif ($receivedConnection->status === 'accepted')
                                            <span class="text-green-500">{{ __('Connected') }}</span>
                                        @else
                                            <span class="text-red-500">{{ __('Request Rejected') }}</span>
                                        @endif
                                    @else
                                        <form action="{{ route('connections.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="connected_user_id" value="{{ $user->id }}">
                                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                {{ __('Connect') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>{{ __('No users available to connect with.') }}</p>
                    @endif
                    
                    <div class="mt-6">
                        <a href="{{ route('connections.index') }}" class="text-blue-500 hover:text-blue-700">
                            {{ __('Back to My Connections') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
