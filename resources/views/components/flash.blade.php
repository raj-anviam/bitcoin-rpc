<div>

    @if($errors->any())
        {!! implode('', $errors->all('<div class="bg-red-100 rounded-lg py-5 px-6 mb-4 text-base text-red-700 mb-3">:message</div>')) !!}
    @endif
    
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
</div>