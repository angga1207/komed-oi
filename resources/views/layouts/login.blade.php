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
    <style>
        .divider:after,
        .divider:before {
            content: "";
            flex: 1;
            height: 1px;
            background: #eee;
        }

        .h-custom {
            height: calc(100% - 73px);
        }

        @media (max-width: 450px) {
            .h-custom {
                height: 100%;
            }
        }
    </style>

    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
</head>

<body>
    <section class="vh-100s">
        <div class="container-fluid h-custom mt-5" style="min-height: 80vh;">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ asset('images/icon.png') }}" style="height: 75px; object-fit:contain" />
                        <div class="text-center flex-grow-1">
                            <h2 class="">
                                Aplikasi Komunikasi Media
                            </h2>
                            <h2 class="">
                                Kabupaten Ogan Ilir
                            </h2>
                        </div>
                    </div>
                    {{-- <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
                        class="img-fluid" alt="Sample image"> --}}

                    <div class="d-flex align-items-center justify-content-center">
                        <dotlottie-player
                            src="https://lottie.host/706006ec-5ef5-4104-920d-01c6c11b16a2/bvnEZ2BGlq.lottie"
                            background="transparent" speed="1" style="width: 80%; height: 80%" loop autoplay>
                        </dotlottie-player>
                    </div>

                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    {{ $slot }}
                </div>
            </div>
        </div>
        <div
            class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5 bg-warning mt-5">
            <!-- Copyright -->
            <div class="text-white mb-3 mb-md-0">
                {{ env('APP_NAME') }} | DISKOMINFO OGAN ILIR |
                Copyright © {{ date('Y') == 2024 ? 2024 : '2024 - '. date('Y') }}
            </div>
            <!-- Copyright -->
        </div>
    </section>


    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <x-livewire-alert::scripts />
    <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
    <x-livewire-alert::flash />
</body>

</html>
