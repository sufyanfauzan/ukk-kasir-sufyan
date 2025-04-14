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

    <div class="mt-8">

        <form action="{{ route('users.update', $data['id']) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block font-medium">Name<span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value={{ $data['name'] }} required
                        class="input input-bordered w-full mt-2" />
                </div>

                <div>
                    <label for="email" class="block font-medium">Email<span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value={{ $data['email'] }} required
                        class="input input-bordered w-full mt-2" />
                </div>

                <div>
                    <label for="password" class="block font-medium">Password<span class="text-red-500">*</span></label>
                    <input type="password" id="password" name="password" class="input input-bordered w-full mt-2" />
                </div>

                <div>
                    <label for="role" class="block font-medium">Role<span class="text-red-500">*</span></label>
                    <select name="role" id="role" class="select select-bordered w-full mt-2">
                        <option disabled selected>Role</option>
                        <option value="admin" {{ $data['role'] === 'admin' ? 'selected' : '' }}> Admin </option>
                        <option value="employee" {{ $data['role'] === 'employee' ? 'selected' : '' }}> Employee </option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('users.index') }}" class="btn btn-error px-8 m-2 text-white">Batal</a>
                <button type="submit" class="btn btn-primary px-8 m-2 text-white">Simpan</button>
            </div>
        </form>

    </div>
@endsection
