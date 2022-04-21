<div>
    <ul>
        @foreach ($data as $key => $value)
            @if (!is_array($value))
                <li>{{ $key }} - {{ $value }}</li>
            @endif
            @endforeach
    </ul>
</div>