<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transactions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- <a href="{{ route('wallets.create') }}" class="inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out">Add New Wallet</a> --}}
                    <button class="get-new-address inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out">Generate New Address</button>

                    <x-flash />

                    <table class="w-full text-sm text-gray-500 dark:text-gray-400 table-responsive">
                        <thead>
                            <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">#</th>
                            <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">address</th>
                            <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">category</th>
                            <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">amount</th>
                            <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">label</th>
                            <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">confirmations</th>
                            <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">blockhash</th>
                            <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">txid</th>
                            <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">timereceived</th>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $value)
                            <tr>
                                <td class="text-sm text-gray-900 font-light px-6 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="text-sm text-gray-900 font-light px-6 whitespace-nowrap">{{ $value['address'] ?? '' }}</td>
                                <td class="text-sm text-gray-900 font-light px-6 whitespace-nowrap">{{ $value['category'] ?? '' }}</td>
                                <td class="text-sm text-gray-900 font-light px-6 whitespace-nowrap">{{ $value['amount'] ?? '' }}</td>
                                <td class="text-sm text-gray-900 font-light px-6 whitespace-nowrap">{{ $value['label'] ?? '' ?? '' }}</td>
                                <td class="text-sm text-gray-900 font-light px-6 whitespace-nowrap">{{ $value['confirmations'] ?? '' }}</td>
                                <td class="text-sm text-gray-900 font-light px-6 whitespace-nowrap">{{ $value['blockhash'] ?? '' }}</td>
                                <td class="text-sm text-gray-900 font-light px-6 whitespace-nowrap">{{ $value['txid'] ?? '' }}</td>
                                <td class="text-sm text-gray-900 font-light px-6 whitespace-nowrap">{{ \Carbon\Carbon::parse($value['timereceived'] ?? '')->format('H:i d-m-Y') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <h4>Generated Addresses</h4>

                    <table class="w-full text-sm text-gray-500 dark:text-gray-400 table-responsive">
                        <thead>
                            <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">#</th>
                            <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">address</th>
                            <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">purpose</th>
                        </thead>
                        <tbody>
                            @foreach($addresses as $key => $value)
                            <tr>
                                <td class="text-sm text-gray-900 font-light px-6 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="text-sm text-gray-900 font-light px-6 whitespace-nowrap">{{ $key }}</td>
                                <td class="text-sm text-gray-900 font-light px-6 whitespace-nowrap">{{ $value['purpose'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    
    
    @push('script')
        <script>
            $('.get-new-address').click(function() {

                if(!confirm('Are You Sure ?'))
                    return;
                
                var id = $(this).data('id');
                $.ajax({
                    url: `{{ route('wallets.get-new-address', $walletId) }}`,
                    success: function(response) {
                        location.reload()
                    }
                })
            })
        </script>
    @endpush

</x-app-layout>
