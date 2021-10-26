@extends('layouts.default')

@section('title', 'Show Credential')

@section('content')

<!-- Page Content -->
<div class="container">
  <h1 class="mt-4">Hello {{ auth()->user()->name }}</h1>
  <h4>Viewing Credential: {{ $credential->description }}</h4>
  @if($mediaItems = $credential->getMedia('files')) 
    @foreach($mediaItems as $media)
        <a href="{{ $media->getUrl() }}" target="_blank">
            <img src="{{ $media->getUrl() }}" class="image-fluid m-2" style="max-width: 50%;" />
        </a>
        <div class="clear"></div>
    @endforeach
  @endif
</div>
@endsection