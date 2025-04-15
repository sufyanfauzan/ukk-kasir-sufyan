@extends('layouts.main')
@section('content')
    <div class="breadcrumbs text-sm">
        <ul>
            <li>
                <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path
                            d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />
                        <path
                            d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />
                    </svg>
                </a>
            </li>
            <li class="font-bold"><a href="#">Dashboard</a></li>
            <li class="font-bold text-gray-400"><a>Users</a></li>
        </ul>
    </div>
    <h1 class="text-4xl font-bold mt-2">USERS</h1>

    <div class="overflow-x-auto mt-8">

        <!-- button -->
        @if (Auth::user()->role === 'admin')
            <div class="flex justify-end mb-4 p-2">
                <a href="{{ route('users.exportUsers') }}" class="btn btn-success text-white">Export</a>
                <a href="{{ route('users.create') }}">
                    <button class="btn btn-primary">Tambah Users</button>
                </a>
            </div>
        @endif

        <!-- table -->
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    @if (Auth::user()->role === 'admin')
                        <th>Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>

                @foreach ($data as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->role }}</td>
                        @if (Auth::user()->role === 'admin')
                        <th>
                            <a href="{{ route('users.edit', $item['id']) }}"
                                class="btn btn-warning btn-sm text-white">Edit</a>
                            <a href="#modal_delete_{{ $item['id'] }}" class="btn btn-error btn-sm text-white">Hapus</a>
                            <!-- Modal -->
                            <div id="modal_delete_{{ $item['id'] }}" class="modal">
                                <div class="modal-box">
                                    <h3 class="text-lg">HAPUS USERS</h3>
                                    <p class="text-lg py-12 text-center">Yakin ingin menghapus data?</p>
                                    <div class="flex justify-end gap-2">
                                        <a href="#" class="btn btn-warning btn-sm text-white">Batal</a>
                                        <form action="{{ route('users.delete', $item['id']) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-error btn-sm text-white">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </th>
                        @endif
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
@endsection
