@extends('layouts.default')

@section('title', 'My Account')

@section('content')

<!-- Page Content -->
<div class="container">
  <h1 class="mt-4">Hello {{ auth()->user()->name }}</h1>
    <a href="{{ route('credentials.create') }}" class="btn btn-primary">Create Credential</a>
    <hr />
    @if(isset($credentials))
        <table class="table table-striped">
            <thead>
                <th>Description</th>
                <th>Files</th>
                <th></th>
            </thead>
            <tbody>
                @foreach($credentials as $credential)
                    <tr>
                        <td> {{ $credential->description }}</td>
                        <td><a class="btn btn-primary" href="{{ route('credentials.show', ['credential' => $credential]) }}">View</a></td>
                        <td>
                            <form action="{{ route('credentials.destroy', [$credential->id]) }}" method="post">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection