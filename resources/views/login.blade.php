@extends('layout.layout')

@section('content')
    <div class="container d-flex justify-content-center p-5 bg-white"
        style="margin-top: 6rem; width:70%; border-radius: 25px; box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;">

        <div class="desc-box mr-5 pr-5 d-none d-md-block" style="width: 40%; text-align: end; border-right: 1px solid #ccc;">
            <h1>F1 Data Dashboard</h1>
            <h3>Ahmad Fauzan Alghifari</h3>
        </div>

        <div class="form-frame" style="width: 40%;">
            <h1>Login</h1>
            <form method="POST" action="/login">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        body {
            background-color: #f8f7f3; /* Specify your desired background color */
        }
    </style>
@endsection
