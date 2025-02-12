<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="MCRDev">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <title>{{ isset($title) ? $title . ' | '. env('APP_NAME') : env('APP_NAME') }}</title>

    <!-- Google font-->
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Bootstrap css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/bootstrap.css')}}">

    <!-- App css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
</head>

<body>

    <!-- login section start -->
    <section class="log-in-section section-b-space">
        <a href="{{ route('dashboard') }}" class="logo-login">
            {{-- <img src="{{ asset('assets/images/logo/1.png') }}" class="img-fluid"> --}}
            <div class="" style="position: absolute;
                width: 100%;
                top: 30px;
                left: 30px;">
                {{-- <img src="{{ asset('images/icon.png') }}" class="img-fluid"
                    style="height: 50px; object-fit:contain"> --}}
                <h1 class="img-fluid">
                    {{ env('APP_NAME') }}
                </h1>
            </div>
        </a>
        <div class="container w-100">
            <div class="row">

                <div class="col-xl-5 col-lg-6 me-auto">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </section>
    <!-- login section end -->


    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <x-livewire-alert::scripts />
    <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
    <x-livewire-alert::flash />

</body>

</html>
