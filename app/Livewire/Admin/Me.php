<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Me extends Component
{
    use WithFileUploads;

    public $detail = [];
    public $photo;
    public $password;
    public $password_confirmation;

    protected $rules = [
        'detail.fullname' => 'required|string|max:255',
        'detail.first_name' => 'nullable|string|max:255',
        'detail.last_name' => 'nullable|string|max:255',
        'detail.username' => 'required|string|max:255|unique:users,username',
        'detail.whatsapp' => 'nullable|string|max:20',
        'detail.email' => 'required|email|unique:users,email',
        'photo' => 'nullable|image|max:2048',
        'password' => 'nullable|min:8|confirmed',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->detail = [
            'id' => $user->id,
            'fullname' => $user->fullname,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'username' => $user->username,
            'whatsapp' => $user->whatsapp ?? '',
            'email' => $user->email,
            'photo' => $user->photo,
        ];
    }

    public function updateProfile()
    {
        // Update validation rules to exclude current user
        $this->rules['detail.username'] = 'required|string|max:255|unique:users,username,' . $this->detail['id'];
        $this->rules['detail.email'] = 'required|email|unique:users,email,' . $this->detail['id'];

        $this->validate();

        $user = Auth::user();

        // Handle photo upload
        if ($this->photo) {
            // Delete old photo if exists
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $photoPath = $this->photo->store('images/users', 'public');
            $this->detail['photo'] = 'storage/' . $photoPath;
        }

        // Update user data
        $user->update([
            'fullname' => $this->detail['fullname'],
            'first_name' => $this->detail['first_name'],
            'last_name' => $this->detail['last_name'],
            'username' => $this->detail['username'],
            'whatsapp' => $this->detail['whatsapp'],
            'email' => $this->detail['email'],
            'photo' => $this->detail['photo'] ?? $user->photo,
        ]);

        // Update password if provided
        if ($this->password) {
            $user->update([
                'password' => Hash::make($this->password)
            ]);
            $this->password = '';
            $this->password_confirmation = '';
        }

        session()->flash('message', 'Profile berhasil diperbarui.');

        // Refresh data
        $this->mount();
    }

    public function render()
    {
        return view('livewire.admin.me');
    }
}
