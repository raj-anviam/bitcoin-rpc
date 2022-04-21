<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Wallets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <a href="{{ route('wallets.create') }}" class="inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out">Add New Wallet</a>

                    @if(Session::has('error'))
                        <div class="bg-red-100 rounded-lg py-5 px-6 mb-4 text-base text-red-700 mb-3" role="alert">
                            {{ Session::get('error') }}
                        </div>
                    @endif
                    @if(Session::has('success'))
                        <div class="bg-green-100 rounded-lg py-5 px-6 mb-4 text-base text-green-700 mb-3" role="alert">
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    
                    
                <table class="w-full text-sm text-gray-500 dark:text-gray-400">
                    <thead>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">#</th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">wallet</th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">Action</th>
                    </thead>
                    <tbody>
                        @foreach($wallets as $key => $wallet)
                            <tr>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">{{ $wallet->name }}</td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    <button data-id={{ $wallet->id }} class="wallet-details inline-block px-6 py-2.5 bg-red-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-red-700 hover:shadow-lg active:bg-red-800">view details</button>
                                    <a href="{{ route('transactions.index', $wallet->id) }}" class="transactions inline-block px-6 py-2.5 bg-red-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-red-700 hover:shadow-lg active:bg-red-800">transactions</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="walletModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Wallet Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                
                </div>  
            </div>
        </div>
    </div>
    
    
    @push('script')
        <script>
            $('.wallet-details').click(function() {
                var id = $(this).data('id');
                $.ajax({
                    url: `{{ url('wallets/show') }}/${id}`,
                    success: function(response) {
                        $('.modal-title').html('Wallet Details');
                        $('.modal-body').html(response)
                        $('#walletModal').modal('show');
                    }
                })
            })

        </script>
    @endpush

</x-app-layout>
