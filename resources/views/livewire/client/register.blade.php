<div>
    <div class="log-in-box">
        <div class="log-in-title mb-4">
            <h3 class="mt-4">Pendaftaran Akun Baru</h3>
            <h4>Aplikasi Komed Ogan Ilir,</h4>
        </div>

        <div class="input-box">
            <form class="row g-4" wire:submit.prevent="register">
                <div class="col-12">
                    <div class="form-floating theme-form-floating log-in-form">
                        <input type="text" class="form-control" id="name" placeholder="Nama Lengkap Sesuai KTP" wire:model="fullname">
                        <label for="name">Nama Lengkap Sesuai KTP</label>
                        @error('fullname')
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-floating theme-form-floating log-in-form">
                        <input type="number" class="form-control" id="nik" placeholder="Masukkan N.I.K" wire:model="username">
                        <label for="nik">Nomor Induk Kependudukan</label>
                        @error('username')
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-floating theme-form-floating log-in-form">
                        <input type="email" class="form-control" id="email" placeholder="Alamat Email" wire:model="email">
                        <label for="email">Alamat Email</label>
                        @error('email')
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-floating theme-form-floating log-in-form">
                        <input type="number" class="form-control" id="handphone"
                            placeholder="Masukkan Nomor Whatsapp" wire:model="whatsapp">
                        <label for="handphone">Nomor Whatsapp</label>

                        @error('whatsapp')
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-floating theme-form-floating log-in-form" x-data={showPassword:false}>
                        <input :type="showPassword ? 'text' : 'password'" class="form-control" id="password" placeholder="Password" wire:model="password">
                        <label for="password">Password</label>

                        <div class="position-absolute top-0 end-0 h-100 d-flex align-items-center">
                            <button type="button" class="btn" @click="showPassword = !showPassword">
                                <div x-show="!showPassword" class="" style="height:1.5rem; width:1.5rem">
                                    <svg fill="#000000" height="1.5rem" width="1.5rem" version="1.1" id="Capa_1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        viewBox="0 0 488.85 488.85" xml:space="preserve">
                                        <g>
                                            <path
                                                d="M244.425,98.725c-93.4,0-178.1,51.1-240.6,134.1c-5.1,6.8-5.1,16.3,0,23.1c62.5,83.1,147.2,134.2,240.6,134.2
                                                    s178.1-51.1,240.6-134.1c5.1-6.8,5.1-16.3,0-23.1C422.525,149.825,337.825,98.725,244.425,98.725z M251.125,347.025
                                                    c-62,3.9-113.2-47.2-109.3-109.3c3.2-51.2,44.7-92.7,95.9-95.9c62-3.9,113.2,47.2,109.3,109.3
                                                    C343.725,302.225,302.225,343.725,251.125,347.025z M248.025,299.625c-33.4,2.1-61-25.4-58.8-58.8c1.7-27.6,24.1-49.9,51.7-51.7
                                                    c33.4-2.1,61,25.4,58.8,58.8C297.925,275.625,275.525,297.925,248.025,299.625z" />
                                        </g>
                                    </svg>
                                </div>
                                <div x-show="showPassword" class="" style="height:1.5rem; width:1.5rem">
                                    {{-- svg eye-off --}}
                                    <svg fill="#000000" width="1.5rem" height="1.5rem" viewBox="0 0 512 512"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <title>ionicons-v5-i</title>
                                        <path
                                            d="M432,448a15.92,15.92,0,0,1-11.31-4.69l-352-352A16,16,0,0,1,91.31,68.69l352,352A16,16,0,0,1,432,448Z" />
                                        <path
                                            d="M248,315.85l-51.79-51.79a2,2,0,0,0-3.39,1.69,64.11,64.11,0,0,0,53.49,53.49A2,2,0,0,0,248,315.85Z" />
                                        <path
                                            d="M264,196.15,315.87,248a2,2,0,0,0,3.4-1.69,64.13,64.13,0,0,0-53.55-53.55A2,2,0,0,0,264,196.15Z" />
                                        <path
                                            d="M491,273.36a32.2,32.2,0,0,0-.1-34.76c-26.46-40.92-60.79-75.68-99.27-100.53C349,110.55,302,96,255.68,96a226.54,226.54,0,0,0-71.82,11.79,4,4,0,0,0-1.56,6.63l47.24,47.24a4,4,0,0,0,3.82,1.05,96,96,0,0,1,116,116,4,4,0,0,0,1.05,3.81l67.95,68a4,4,0,0,0,5.4.24A343.81,343.81,0,0,0,491,273.36Z" />
                                        <path
                                            d="M256,352a96,96,0,0,1-93.3-118.63,4,4,0,0,0-1.05-3.81L94.81,162.69a4,4,0,0,0-5.41-.23c-24.39,20.81-47,46.13-67.67,75.72a31.92,31.92,0,0,0-.64,35.54c26.41,41.33,60.39,76.14,98.28,100.65C162.06,402,207.92,416,255.68,416a238.22,238.22,0,0,0,72.64-11.55,4,4,0,0,0,1.61-6.64l-47.47-47.46a4,4,0,0,0-3.81-1.05A96,96,0,0,1,256,352Z" />
                                    </svg>
                                </div>
                            </button>
                        </div>

                        @error('password')
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-floating theme-form-floating log-in-form" x-data={showPassword:false}>
                        <input :type="showPassword ? 'text' : 'password'" class="form-control"
                            id="password_confirmation" placeholder="Kata Sandi" wire:model='password_confirmation'>
                        <label for="confirm_password">Konfirmasi Kata Sandi</label>

                        <div class="position-absolute top-0 end-0 h-100 d-flex align-items-center">
                            <button type="button" class="btn" @click="showPassword = !showPassword">
                                <div x-show="!showPassword" class="" style="height:1.5rem; width:1.5rem">
                                    <svg fill="#000000" height="1.5rem" width="1.5rem" version="1.1" id="Capa_1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        viewBox="0 0 488.85 488.85" xml:space="preserve">
                                        <g>
                                            <path
                                                d="M244.425,98.725c-93.4,0-178.1,51.1-240.6,134.1c-5.1,6.8-5.1,16.3,0,23.1c62.5,83.1,147.2,134.2,240.6,134.2
                                                    s178.1-51.1,240.6-134.1c5.1-6.8,5.1-16.3,0-23.1C422.525,149.825,337.825,98.725,244.425,98.725z M251.125,347.025
                                                    c-62,3.9-113.2-47.2-109.3-109.3c3.2-51.2,44.7-92.7,95.9-95.9c62-3.9,113.2,47.2,109.3,109.3
                                                    C343.725,302.225,302.225,343.725,251.125,347.025z M248.025,299.625c-33.4,2.1-61-25.4-58.8-58.8c1.7-27.6,24.1-49.9,51.7-51.7
                                                    c33.4-2.1,61,25.4,58.8,58.8C297.925,275.625,275.525,297.925,248.025,299.625z" />
                                        </g>
                                    </svg>
                                </div>
                                <div x-show="showPassword" class="" style="height:1.5rem; width:1.5rem">
                                    {{-- svg eye-off --}}
                                    <svg fill="#000000" width="1.5rem" height="1.5rem" viewBox="0 0 512 512"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <title>ionicons-v5-i</title>
                                        <path
                                            d="M432,448a15.92,15.92,0,0,1-11.31-4.69l-352-352A16,16,0,0,1,91.31,68.69l352,352A16,16,0,0,1,432,448Z" />
                                        <path
                                            d="M248,315.85l-51.79-51.79a2,2,0,0,0-3.39,1.69,64.11,64.11,0,0,0,53.49,53.49A2,2,0,0,0,248,315.85Z" />
                                        <path
                                            d="M264,196.15,315.87,248a2,2,0,0,0,3.4-1.69,64.13,64.13,0,0,0-53.55-53.55A2,2,0,0,0,264,196.15Z" />
                                        <path
                                            d="M491,273.36a32.2,32.2,0,0,0-.1-34.76c-26.46-40.92-60.79-75.68-99.27-100.53C349,110.55,302,96,255.68,96a226.54,226.54,0,0,0-71.82,11.79,4,4,0,0,0-1.56,6.63l47.24,47.24a4,4,0,0,0,3.82,1.05,96,96,0,0,1,116,116,4,4,0,0,0,1.05,3.81l67.95,68a4,4,0,0,0,5.4.24A343.81,343.81,0,0,0,491,273.36Z" />
                                        <path
                                            d="M256,352a96,96,0,0,1-93.3-118.63,4,4,0,0,0-1.05-3.81L94.81,162.69a4,4,0,0,0-5.41-.23c-24.39,20.81-47,46.13-67.67,75.72a31.92,31.92,0,0,0-.64,35.54c26.41,41.33,60.39,76.14,98.28,100.65C162.06,402,207.92,416,255.68,416a238.22,238.22,0,0,0,72.64-11.55,4,4,0,0,0,1.61-6.64l-47.47-47.46a4,4,0,0,0-3.81-1.05A96,96,0,0,1,256,352Z" />
                                    </svg>
                                </div>
                            </button>
                        </div>

                        @error('password_confirmation')
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
                    <button type="submit" class="btn btn-animation w-100 justify-content-center">Buat Akun</button>
                    <h5 class="new-page mt-3 text-start">Sudah memilik akun? <a href="{{ route('login') }}" style="color: #1b71FF !important">Login  Komed </a></h5>
                </div>
            </form>
        </div>
    </div>
</div>
