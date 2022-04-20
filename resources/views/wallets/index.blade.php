<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <a href="{{ route('wallets.create') }}">Add New Wallet</a>
                    
                    
                <table class="w-full text-sm text-gray-500 dark:text-gray-400">
                    <thead>
                        <th>#</th>
                        <th>wallet</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach($wallets as $key => $wallet)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $wallet->name }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

