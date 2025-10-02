<div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="title-header option-title">
                        <h5>Update Profile</h5>
                    </div>

                    @if (session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form class="row g-3" wire:submit.prevent="updateProfile">
                        <!-- Current Photo -->
                        @if($detail['photo'] ?? false)
                        <div class="col-12">
                            <label class="col-form-label">Foto Saat Ini:</label>
                            <div>
                                <img src="{{ asset($detail['photo']) }}" alt="Current Photo"
                                     class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                            </div>
                        </div>
                        @endif

                        <!-- Photo Upload -->
                        <div class="col-12 col-xl-6">
                            <label for="photo" class="col-form-label">Foto Profil Baru:</label>
                            <input type="file" class="form-control" id="photo" wire:model="photo" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG, JPEG (Max: 2MB)</small>

                            @error('photo')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror

                            @if ($photo)
                            <div class="mt-2">
                                <img src="{{ $photo->temporaryUrl() }}" alt="Preview"
                                     class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                            </div>
                            @endif
                        </div>

                        <!-- Fullname -->
                        <div class="col-12 col-xl-6">
                            <label for="fullname" class="col-form-label">Nama Lengkap: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="fullname" autocomplete="off"
                                   placeholder="Nama Lengkap" wire:model="detail.fullname">

                            @error('detail.fullname')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- First Name -->
                        <div class="col-12 col-xl-6">
                            <label for="first_name" class="col-form-label">Nama Depan:</label>
                            <input type="text" class="form-control" id="first_name" autocomplete="off"
                                   placeholder="Nama Depan" wire:model="detail.first_name">

                            @error('detail.first_name')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="col-12 col-xl-6">
                            <label for="last_name" class="col-form-label">Nama Belakang:</label>
                            <input type="text" class="form-control" id="last_name" autocomplete="off"
                                   placeholder="Nama Belakang" wire:model="detail.last_name">

                            @error('detail.last_name')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div class="col-12 col-xl-6">
                            <label for="username" class="col-form-label">Username: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" autocomplete="off"
                                   placeholder="Username" wire:model="detail.username">

                            @error('detail.username')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- WhatsApp -->
                        <div class="col-12 col-xl-6">
                            <label for="whatsapp" class="col-form-label">WhatsApp:</label>
                            <input type="text" class="form-control" id="whatsapp" autocomplete="off"
                                   placeholder="08xxxxxxxxxx" wire:model="detail.whatsapp">

                            @error('detail.whatsapp')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-12 col-xl-6">
                            <label for="email" class="col-form-label">Email: <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" autocomplete="off"
                                   placeholder="email@example.com" wire:model="detail.email">

                            @error('detail.email')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Divider -->
                        <div class="col-12">
                            <hr>
                            <h6 class="mb-3">Ubah Password (Kosongkan jika tidak ingin mengubah)</h6>
                        </div>

                        <!-- Password -->
                        <div class="col-12 col-xl-6">
                            <label for="password" class="col-form-label">Password Baru:</label>
                            <div class="position-relative" x-data="{showPassword:false}">
                                <input :type="showPassword ? 'text' : 'password'" class="form-control"
                                       id="password" autocomplete="off" placeholder="Minimal 8 karakter"
                                       wire:model="password">

                                <div class="position-absolute top-0 end-0 h-100 d-flex align-items-center">
                                    <button type="button" class="btn" @click="showPassword = !showPassword">
                                        <div x-show="!showPassword" class="" style="height:1.5rem; width:1.5rem">
                                            <svg fill="#000000" height="1.5rem" width="1.5rem" version="1.1"
                                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 488.85 488.85">
                                                <g>
                                                    <path d="M244.425,98.725c-93.4,0-178.1,51.1-240.6,134.1c-5.1,6.8-5.1,16.3,0,23.1c62.5,83.1,147.2,134.2,240.6,134.2
                                                        s178.1-51.1,240.6-134.1c5.1-6.8,5.1-16.3,0-23.1C422.525,149.825,337.825,98.725,244.425,98.725z M251.125,347.025
                                                        c-62,3.9-113.2-47.2-109.3-109.3c3.2-51.2,44.7-92.7,95.9-95.9c62-3.9,113.2,47.2,109.3,109.3
                                                        C343.725,302.225,302.225,343.725,251.125,347.025z M248.025,299.625c-33.4,2.1-61-25.4-58.8-58.8c1.7-27.6,24.1-49.9,51.7-51.7
                                                        c33.4-2.1,61,25.4,58.8,58.8C297.925,275.625,275.525,297.925,248.025,299.625z" />
                                                </g>
                                            </svg>
                                        </div>
                                        <div x-show="showPassword" class="" style="height:1.5rem; width:1.5rem">
                                            <svg fill="#000000" width="1.5rem" height="1.5rem" viewBox="0 0 512 512"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path d="M432,448a15.92,15.92,0,0,1-11.31-4.69l-352-352A16,16,0,0,1,91.31,68.69l352,352A16,16,0,0,1,432,448Z" />
                                                <path d="M248,315.85l-51.79-51.79a2,2,0,0,0-3.39,1.69,64.11,64.11,0,0,0,53.49,53.49A2,2,0,0,0,248,315.85Z" />
                                                <path d="M264,196.15,315.87,248a2,2,0,0,0,3.4-1.69,64.13,64.13,0,0,0-53.55-53.55A2,2,0,0,0,264,196.15Z" />
                                                <path d="M491,273.36a32.2,32.2,0,0,0-.1-34.76c-26.46-40.92-60.79-75.68-99.27-100.53C349,110.55,302,96,255.68,96a226.54,226.54,0,0,0-71.82,11.79,4,4,0,0,0-1.56,6.63l47.24,47.24a4,4,0,0,0,3.82,1.05,96,96,0,0,1,116,116,4,4,0,0,0,1.05,3.81l67.95,68a4,4,0,0,0,5.4.24A343.81,343.81,0,0,0,491,273.36Z" />
                                                <path d="M256,352a96,96,0,0,1-93.3-118.63,4,4,0,0,0-1.05-3.81L94.81,162.69a4,4,0,0,0-5.41-.23c-24.39,20.81-47,46.13-67.67,75.72a31.92,31.92,0,0,0-.64,35.54c26.41,41.33,60.39,76.14,98.28,100.65C162.06,402,207.92,416,255.68,416a238.22,238.22,0,0,0,72.64-11.55,4,4,0,0,0,1.61-6.64l-47.47-47.46a4,4,0,0,0-3.81-1.05A96,96,0,0,1,256,352Z" />
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </div>

                            @error('password')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div class="col-12 col-xl-6">
                            <label for="password_confirmation" class="col-form-label">Konfirmasi Password:</label>
                            <div class="position-relative" x-data="{showPassword:false}">
                                <input :type="showPassword ? 'text' : 'password'" class="form-control"
                                       id="password_confirmation" autocomplete="off" placeholder="Konfirmasi Password"
                                       wire:model="password_confirmation">

                                <div class="position-absolute top-0 end-0 h-100 d-flex align-items-center">
                                    <button type="button" class="btn" @click="showPassword = !showPassword">
                                        <div x-show="!showPassword" class="" style="height:1.5rem; width:1.5rem">
                                            <svg fill="#000000" height="1.5rem" width="1.5rem" version="1.1"
                                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 488.85 488.85">
                                                <g>
                                                    <path d="M244.425,98.725c-93.4,0-178.1,51.1-240.6,134.1c-5.1,6.8-5.1,16.3,0,23.1c62.5,83.1,147.2,134.2,240.6,134.2
                                                        s178.1-51.1,240.6-134.1c5.1-6.8,5.1-16.3,0-23.1C422.525,149.825,337.825,98.725,244.425,98.725z M251.125,347.025
                                                        c-62,3.9-113.2-47.2-109.3-109.3c3.2-51.2,44.7-92.7,95.9-95.9c62-3.9,113.2,47.2,109.3,109.3
                                                        C343.725,302.225,302.225,343.725,251.125,347.025z M248.025,299.625c-33.4,2.1-61-25.4-58.8-58.8c1.7-27.6,24.1-49.9,51.7-51.7
                                                        c33.4-2.1,61,25.4,58.8,58.8C297.925,275.625,275.525,297.925,248.025,299.625z" />
                                                </g>
                                            </svg>
                                        </div>
                                        <div x-show="showPassword" class="" style="height:1.5rem; width:1.5rem">
                                            <svg fill="#000000" width="1.5rem" height="1.5rem" viewBox="0 0 512 512"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path d="M432,448a15.92,15.92,0,0,1-11.31-4.69l-352-352A16,16,0,0,1,91.31,68.69l352,352A16,16,0,0,1,432,448Z" />
                                                <path d="M248,315.85l-51.79-51.79a2,2,0,0,0-3.39,1.69,64.11,64.11,0,0,0,53.49,53.49A2,2,0,0,0,248,315.85Z" />
                                                <path d="M264,196.15,315.87,248a2,2,0,0,0,3.4-1.69,64.13,64.13,0,0,0-53.55-53.55A2,2,0,0,0,264,196.15Z" />
                                                <path d="M491,273.36a32.2,32.2,0,0,0-.1-34.76c-26.46-40.92-60.79-75.68-99.27-100.53C349,110.55,302,96,255.68,96a226.54,226.54,0,0,0-71.82,11.79a4,4,0,0,0-1.56,6.63l47.24,47.24a4,4,0,0,0,3.82,1.05,96,96,0,0,1,116,116,4,4,0,0,0,1.05,3.81l67.95,68a4,4,0,0,0,5.4.24A343.81,343.81,0,0,0,491,273.36Z" />
                                                <path d="M256,352a96,96,0,0,1-93.3-118.63,4,4,0,0,0-1.05-3.81L94.81,162.69a4,4,0,0,0-5.41-.23c-24.39,20.81-47,46.13-67.67,75.72a31.92,31.92,0,0,0-.64,35.54c26.41,41.33,60.39,76.14,98.28,100.65C162.06,402,207.92,416,255.68,416a238.22,238.22,0,0,0,72.64-11.55,4,4,0,0,0,1.61-6.64l-47.47-47.46a4,4,0,0,0-3.81-1.05A96,96,0,0,1,256,352Z" />
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-theme">
                                <i class="ri-save-line me-2"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
