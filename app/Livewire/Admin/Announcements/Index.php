<?php

namespace App\Livewire\Admin\Announcements;

use App\Models\Announcement;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithPagination, WithFileUploads, LivewireAlert;
    protected $paginationTheme = 'bootstrap';

    public $search;
    public $detail = [], $inputType = 'create';

    public $activeId, $inactiveId;

    function getListeners()
    {
        return [
            'inactive' => 'inactive',
            'activated' => 'activated',
        ];
    }

    function mount()
    {
        $this->inputType = 'create';
    }

    function addData()
    {
        $this->resetErrorBag();
        $this->inputType = 'create';
        $this->detail = [
            'id' => null,
            'title' => null,
            'content' => null,
            'image' => null,
            'link' => null,
            'is_active' => true,
            'published_at' => now()->format('Y-m-d'),
        ];
    }

    function saveData()
    {
        if ($this->inputType == 'create') {
            $this->validate([
                'detail.title' => 'required',
                'detail.content' => 'required',
                'detail.image' => 'required',
                'detail.published_at' => 'required',
            ], [], [
                'detail.title' => 'Judul',
                'detail.content' => 'Konten',
                'detail.image' => 'Gambar',
                'detail.published_at' => 'Tanggal Publish',
            ]);

            DB::table('announcements')->insert([
                'title' => $this->detail['title'],
                'content' => $this->detail['content'],
                'image' => $this->detail['image'],
                'link' => $this->detail['link'],
                'is_active' => $this->detail['is_active'],
                'published_at' => $this->detail['published_at'],
                'created_by' => auth()->id(),
                'created_at' => now(),
            ]);

            if ($this->detail['image']) {
                $fileName = time() . '.' . $this->detail['image']->extension();
                $path = $this->detail['image']->storeAs('images/announcements/', $fileName, 'public');
                DB::table('announcements')
                    ->where('image', $this->detail['image'])
                    ->update([
                        'image' => '/storage/images/announcements/' . $fileName,
                    ]);

                $this->detail['image'] = null;
            }

            $this->alert('success', 'Data berhasil disimpan', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Data berhasil disimpan',
                'showCancelButton' => true,
                'showConfirmButton' => false,
                'cancelButtonText' => 'Tutup',
                'confirmText' => '',
            ]);

            // make log start
            $log = [
                'id' => uniqid(),
                'user_id' => auth()->id(),
                'action' => 'create',
                'model' => 'announcements',
                'endpoint' => 'announcements',
                'payload' => json_encode(request()->all()),
                'message' => 'Menambahkan pengumuman baru dengan judul ' . $this->detail['title'],
                'created_at' => now()
            ];
            DB::table('user_logs')->insert($log);
            // make log end
        } else if ($this->inputType == 'update') {
            $this->validate([
                'detail.title' => 'required',
                'detail.content' => 'required',
                'detail.image' => 'required',
                'detail.published_at' => 'required',
            ], [], [
                'detail.title' => 'Judul',
                'detail.content' => 'Konten',
                'detail.image' => 'Gambar',
                'detail.published_at' => 'Tanggal Publish',
            ]);

            $oldImage = DB::table('announcements')
                ->where('id', $this->detail['id'])
                ->first()
                ->image;

            DB::table('announcements')
                ->where('id', $this->detail['id'])
                ->update([
                    'title' => $this->detail['title'],
                    'content' => $this->detail['content'],
                    'link' => $this->detail['link'],
                    'image' => $this->detail['image'],
                    'is_active' => $this->detail['is_active'],
                    'published_at' => $this->detail['published_at'],
                ]);

            // check if image is updated
            if ($this->detail['image'] && $oldImage != $this->detail['image']) {
                $fileName = time() . '.' . $this->detail['image']->extension();
                $path = $this->detail['image']->storeAs('images/announcements/', $fileName, 'public');
                DB::table('announcements')
                    ->where('image', $this->detail['image'])
                    ->update([
                        'image' => '/storage/images/announcements/' . $fileName,
                    ]);

                $this->detail['image'] = null;
            }

            $this->alert('success', 'Data berhasil diperbarui', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Data berhasil diperbarui',
                'showCancelButton' => true,
                'showConfirmButton' => false,
                'cancelButtonText' => 'Tutup',
                'confirmText' => '',
            ]);

            // make log start
            $log = [
                'id' => uniqid(),
                'user_id' => auth()->id(),
                'action' => 'update',
                'model' => 'announcements',
                'endpoint' => 'announcements',
                'payload' => json_encode(request()->all()),
                'message' => 'Mengubah pengumuman dengan judul ' . $this->detail['title'],
                'created_at' => now()
            ];

            DB::table('user_logs')->insert($log);
            // make log end
        }
        $this->resetErrorBag();
        $this->dispatch('closeModal');
        $this->closeModal();
    }

    function getDetail($id)
    {
        $this->resetErrorBag();
        $this->detail = Announcement::where('id', $id)
            ->first()
            ->toArray();
        $this->inputType = 'update';
    }

    function closeModal()
    {
        $this->detail = null;
        $this->inputType = 'create';
    }

    public function render()
    {
        $announcements = Announcement::search($this->search)
            ->latest()
            ->paginate(10);

        return view('livewire.admin.announcements.index', [
            'announcements' => $announcements,
        ])->layout('layouts.app', ['title' => 'Pengumuman']);
    }

    function confirmActivated($id)
    {
        $this->confirm('Apakah Anda yakin ingin mengaktifkan kembali pengumuman ini?', [
            'text' => 'Pengumuman ini berhasil diaktifkan',
            'toast' => false,
            'position' => 'center',
            'timer' => null,
            'showCancelButton' => true,
            'showConfirmButton' => true,
            'cancelButtonText' => 'Batal',
            'confirmButtonText' => 'Aktifkan',
            'onConfirmed' => 'activated'
        ]);
        $this->activeId = $id;
    }

    function activated()
    {
        $announcement = Announcement::find($this->activeId);

        if ($announcement) {
            $announcement->is_active = true;
            $announcement->save();

            $this->alert('success', 'Pengumuman ini berhasil diaktifkan', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Pengumuman ini berhasil diaktifkan',
                'showCancelButton' => false,
                'showConfirmButton' => true,
                'cancelText' => '',
                'confirmText' => 'Tutup',
            ]);
        }

        $this->activeId = null;

        // make log start
        $log = [
            'id' => uniqid(),
            'user_id' => auth()->id(),
            'action' => 'active',
            'model' => 'announcements',
            'endpoint' => 'announcements',
            'payload' => json_encode(request()->all()),
            'message' => 'Mengaktifkan kembali pengumuman dengan judul ' . $announcement->title,
            'created_at' => now()
        ];
        DB::table('user_logs')->insert($log);
        // make log end
    }

    function confirmInactive($id)
    {
        $this->confirm('Apakah Anda yakin ingin menonaktifkan pengumuman ini?', [
            'text' => 'Pengumuman ini berhasil dinonaktifkan',
            'toast' => false,
            'position' => 'center',
            'timer' => null,
            'showCancelButton' => true,
            'showConfirmButton' => true,
            'cancelButtonText' => 'Batal',
            'confirmButtonText' => 'Nonaktifkan',
            'onConfirmed' => 'inactive'
        ]);
        $this->inactiveId = $id;
    }

    function inactive()
    {
        $announcement = Announcement::find($this->inactiveId);

        if ($announcement) {
            $announcement->is_active = false;
            $announcement->save();

            $this->alert('success', 'Pengumuman ini berhasil dinonaktifkan', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Pengumuman ini berhasil dinonaktifkan',
                'showCancelButton' => false,
                'showConfirmButton' => true,
                'cancelText' => '',
                'confirmText' => 'Tutup',
            ]);
        }

        $this->inactiveId = null;

        // make log start
        $log = [
            'id' => uniqid(),
            'user_id' => auth()->id(),
            'action' => 'inactive',
            'model' => 'announcements',
            'endpoint' => 'announcements',
            'payload' => json_encode(request()->all()),
            'message' => 'Menonaktifkan pengumuman dengan judul ' . $announcement->title,
            'created_at' => now()
        ];
        DB::table('user_logs')->insert($log);
        // make log end
    }
}
