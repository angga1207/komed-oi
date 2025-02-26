
<div class="log-in-box">

    <div class="log-in-title mb-4">
        <a href=""><img src="{{ asset('images/logobersama.png') }}" class="img-fluid rounded"></a>
        <h3 class="mt-4">Aplikasi Komed Ogan Ilir</h3>
        <h4>Selamat Datang,</h4>
    </div>

    <div class="input-box">
        <form wire:submit.prevent="login" class="row g-4">
            <div class="col-12">
                <div class="form-floating theme-form-floating log-in-form">
                    <input type="text" class="form-control" id="email" placeholder="Username / N.I.K" wire:model='username'>
                    <label for="email">Username / N.I.K</label>
                    @error('username')
                    <div class="text-danger mt-1" style="font-size: 0.8rem;">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="col-12">
                <div class="form-floating theme-form-floating log-in-form">
                    <input type="password" class="form-control" id="password" placeholder="Password" wire:model='password'>
                    <label for="password">Password</label>
                    @error('password')
                    <div class="text-danger mt-1" style="font-size: 0.8rem;">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-animation w-100 justify-content-center">Log In</button>
                <h5 class="new-page mt-3 text-start">Belum memilik akun? <a href="{{ route('register') }}" style="color: #1b71FF !important">Buat Akun</a></h5>
            </div>
        </form>
    </div>

    <div class="other-log-in mt-5">
        <h6>Download Aplikasi Komed Mobile</h6>
    </div>

    <div class="d-flex justify-content-center mt-3">
        <a href="#" type="button" class="btn google-button">
            <img src="{{ asset('images/ps.webp') }}" class="blur-up lazyload me-2" width="25"
                alt="">
            Playstore
        </a>
        <a href="#" type="button" class="btn google-button">
            <img src="{{ asset('images/appstore.png') }}" class="blur-up lazyload me-2"
                width="25" alt="">
            Apps Store
        </a>
    </div>

    <div class="text-center mt-5" style="color: darkgray;">
        <h6>
            {{ env('APP_NAME') }} | DISKOMINFO OGAN ILIR |
            Copyright © {{ date('Y') == 2024 ? 2024 : '2024 - '. date('Y') }}
        </h6>
    </div>

</div>
