
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

            {{-- Captcha --}}
            <div class="col-12">
                <div class="d-flex flex-column justify-content-center rounded mb-3">
                    <div class="captcha-container rounded">
                        <div class="captcha-wrapper">
                          <div class="d-flex flex-column flex-md-row align-items-center">
                            <div class="captcha-image-container border rounded p-2 bg-light">
                              {!! captcha_img() !!}
                            </div>
                            <button class="refresh-button btn btn-outline-primary d-flex align-items-center ms-md-3 mt-3 mt-md-0" id="reload" wire:click.prevent="reloadCaptcha()">
                              <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" fill="currentColor" width="16" height="16" viewBox="0 0 30 30" class="me-2">
                                <path d="M 15 3 C 12.053086 3 9.3294211 4.0897803 7.2558594 5.8359375 A 1.0001 1.0001 0 1 0 8.5449219 7.3652344 C 10.27136 5.9113916 12.546914 5 15 5 C 20.226608 5 24.456683 8.9136179 24.951172 14 L 22 14 L 26 20 L 30 14 L 26.949219 14 C 26.441216 7.8348596 21.297943 3 15 3 z M 4.3007812 9 L 0.30078125 15 L 3 15 C 3 21.635519 8.3644809 27 15 27 C 17.946914 27 20.670579 25.91022 22.744141 24.164062 A 1.0001 1.0001 0 1 0 21.455078 22.634766 C 19.72864 24.088608 17.453086 25 15 25 C 9.4355191 25 5 20.564481 5 15 L 8.3007812 15 L 4.3007812 9 z"></path>
                                </svg>
                              Refresh
                            </button>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="form-floating theme-form-floating log-in-form">
                    <input type="text" class="form-control" id="captcha" placeholder="Captcha" wire:model='captcha'>
                    <label for="captcha">Captcha</label>
                    @error('captcha')
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
