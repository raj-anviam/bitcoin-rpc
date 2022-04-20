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
                    
                <form action="{{ route('wallets.store') }}" method="POST">
                    @csrf
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                        <input type="text" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  placeholder="Enter a Wallet Name" required>
                    </div>
                    <div class="text-center">
                        <input type="submit" class="shadow appearance-none border rounded p-2 mt-4">
                    </div>
                </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

