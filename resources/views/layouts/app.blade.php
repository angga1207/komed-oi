<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="MCRDev">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    {{-- csrf token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' | '. env('APP_NAME') : env('APP_NAME') }}</title>

    <!-- Google font-->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">

    <!-- Linear Icon css -->
    <link rel="stylesheet" href="{{ asset('assets/css/linearicon.css') }}">

    <!-- fontawesome css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/font-awesome.css') }}">

    <!-- Themify icon css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/themify.css') }}">

    <!-- ratio css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ratio.css') }}">

    <!-- remixicon css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/remixicon.css') }}">

    <!-- Feather icon css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/feather-icon.css') }}">

    <!-- Plugins css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/scrollbar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">

    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/bootstrap.css') }}">

    <!-- vector map css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vector-map.css') }}">

    <!-- Slick Slider Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/slick.css') }}">

    <!-- App css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">

    @stack('styles')
</head>

<body>
    @if (in_array(auth()->user()->role_id, [4]))
    @if(Route::currentRouteName() !== 'firstUpdateMedia')
    @livewire('client.media-not-set')
    @endif
    @endif
    <!-- tap on top start -->
    <div class="tap-top">
        <span class="lnr lnr-chevron-up"></span>
    </div>
    <!-- tap on tap end -->

    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">


        @livewire('admin.components.navbar')

        <!-- Page Body Start-->
        <div class="page-body-wrapper">

            @livewire('admin.components.sidebar')

            <!-- index body start -->
            <div class="page-body">
                <div class="container-fluid">
                    {{ $slot }}
                </div>
                <!-- Container-fluid Ends-->

                @livewire('admin.components.footer')
            </div>
            <!-- index body end -->

        </div>
        <!-- Page Body End -->
    </div>
    <!-- page-wrapper End-->

    <!-- Modal Start -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h5 class="modal-title" id="staticBackdropLabel">Logging Out</h5>
                    <p>Are you sure you want to log out?</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="button-box">
                        <button type="button" class="btn btn--no" data-bs-dismiss="modal">No</button>
                        <button type="button" onclick="location.href = 'login.html';"
                            class="btn  btn--yes btn-primary">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End -->

    <!-- latest js -->
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>

    <!-- Bootstrap js -->
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <!-- feather icon js -->
    <script src="{{ asset('assets/js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js') }}"></script>

    <!-- scrollbar simplebar js -->
    <script src="{{ asset('assets/js/scrollbar/simplebar.js') }}"></script>
    <script src="{{ asset('assets/js/scrollbar/custom.js') }}"></script>

    <!-- Sidebar jquery -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- tooltip init js -->
    <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>

    <!-- Plugins JS -->
    <script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/notify/index.js') }}"></script> --}}

    <!-- Apexchar js -->
    {{-- <script src="{{ asset('assets/js/chart/apex-chart/apex-chart1.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/chart-custom1.js') }}"></script> --}}


    <!-- slick slider js -->
    <script src="{{ asset('assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom-slick.js') }}"></script>

    <!-- customizer js -->
    {{-- <script src="{{ asset('assets/js/customizer.js') }}"></script> --}}

    <!-- ratio js -->
    <script src="{{ asset('assets/js/ratio.js') }}"></script>

    <!-- sidebar effect -->
    <script src="{{ asset('assets/js/sidebareffect.js') }}"></script>

    <!-- Theme js -->
    <script src="{{ asset('assets/js/script.js') }}"></script>

    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js"></script>
    <script>
        // Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyCIjxnhXyvF7uQsgEyU8jX99_7p_tqa1x0",
            authDomain: "komed-oi.firebaseapp.com",
            projectId: "komed-oi",
            storageBucket: "komed-oi.firebasestorage.app",
            messagingSenderId: "67197346584",
            appId: "1:67197346584:web:06d5065a8e2709938db46d",
            measurementId: "G-GM74W7Z9XY"
        };

        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        // Register service worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/firebase-messaging-sw.js')
            .then((registration) => {
                // console.log('Service Worker registered:', registration);
            })
            .catch((error) => {
                // console.error('Service Worker registration failed:', error);
            });
        }

        // Request notification permission
        function requestNotificationPermission() {
            Notification.requestPermission().then((permission) => {
            if (permission === 'granted') {
                messaging.getToken({ vapidKey: 'BJePlygL0zdt8wF8K01lrByWH-kSODlpFaWlB1vI78yATY0ljLGSU6hmHgUmlkGjboNI3dNcA7_rQ3lY45KD6dA' }).then((token) => {
                // console.log('FCM Token:', token);
                if(token){
                    // console.log('currentToken:', currentToken);
                    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ route('updateFcmToken') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        type: "POST",
                        data: {
                            fcm_token: token,
                            type:'web',
                        },
                        success: function(response) {
                            // console.log('fcm token updated');
                        }
                    });
                }
                }).catch((error) => {
                    console.error('Error getting FCM token:', error);
                });
            }
            });
        }
        requestNotificationPermission();

        // Handle foreground messages
        // messaging.onMessage((payload) => {
        //     // console.log('Message received in foreground:', payload);
        //     // new Notification(payload.notification?.title, {
        //     //     body: payload.notification?.body,
        //     //     icon: '/firebase-logo.png' // Replace with your own icon
        //     // });
        // });
    </script>
    <script>
        document.addEventListener('livewire:init', () => {
        // catch display event
        // window.livewire.on('closeModal', (message, type) => {
        //     $('.modal').modal('hide');
        // });

        Livewire.on('closeModal', () => {
            $('.modal').modal('hide');
        });
    })
    </script>

    @stack('scripts')

    @livewireScripts
    @livewireChartsScripts
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <x-livewire-alert::scripts />
    <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
    <x-livewire-alert::flash />

</body>

</html>
