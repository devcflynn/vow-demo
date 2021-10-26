@extends('layouts.default')

@section('title', 'Register')

@section('content')
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card border-0 shadow rounded-3 my-5">
                <div class="card-body p-4 p-sm-5">
                    <h5 class="card-title text-center mb-5 fw-light fs-5">Create Account</h5>
                    <form action="/register" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-floating mb-3">
                            <input name="name" 
                                type="text" 
                                class="form-control"
                                value="{{ old('name') }}"
                                placeholder="Name">
                            <label for="floatingInput">Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input name="email" 
                                type="email" 
                                class="form-control"
                                value="{{ old('email') }}"
                                placeholder="name@example.com">
                            <label for="floatingInput">Email address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input name="password" type="password" class="form-control"
                                placeholder="Password">
                            <label for="floatingPassword">Password</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input name="password_confirmation" type="password" class="form-control"
                                placeholder="Password">
                            <label for="floatingPassword">Confirm Password</label>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary btn-login text-uppercase fw-bold" type="submit">Create Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

