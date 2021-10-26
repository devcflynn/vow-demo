<div
    class="alert p-2
        @if (isset($type)) alert-{{ $type }} @else alert-info @endif
        @if (isset($dismissable) && $dismissable) alert-dismissible @endif
    "
    role="alert"
>
    @if (isset($title) && $title)<h4>{{ $title }}</h4>@endif
    @if (isset($dismissable) && $dismissable)<a class="close mx-2 pull-right" data-dismiss="alert" title="Dismiss"><i class="fas fa-times"></i> </a>@endif
    {{ $slot }}
</div>
