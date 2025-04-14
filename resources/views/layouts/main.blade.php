<!doctype html>
<html data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>

<body>
    <div class="drawer lg:drawer-open shadow-lg">
        <!-- Sidebar -->
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
        <div class="drawer-side">
            <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>
            <ul class="menu text-base-content min-h-full w-80 p-6 space-y-4">
                <li>
                    <a href="{{ route('dashboard.index') }}"
                        class="text-lg font-semibold py-2 px-4 rounded-lg hover:bg-base-300 transition duration-300">Dashboard</a>
                </li>
                <li>
                    <a href="{{ route('products.index') }}"
                        class="text-lg font-semibold py-2 px-4 rounded-lg hover:bg-base-300 transition duration-300">Products</a>
                </li>
                <li>
                    <a href="{{ route('sales.index') }}"
                        class="text-lg font-semibold py-2 px-4 rounded-lg hover:bg-base-300 transition duration-300">Sale</a>
                </li>
                <li>
                    <a href="{{ route('users.index') }}"
                        class="text-lg font-semibold py-2 px-4 rounded-lg hover:bg-base-300 transition duration-300">Users</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="drawer-content flex flex-col">
            <!-- Navbar -->
            <div class="navbar bg-base-100 shadow-md w-full p-4 flex items-center justify-between">
                <div class="flex-none lg:hidden">
                    <label for="my-drawer-2" aria-label="open sidebar" class="btn btn-square btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            class="inline-block h-6 w-6 stroke-current">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </label>
                </div>

                <!-- Spacer -->
                <div class="flex-1"></div>

                <!-- Dropdown -->
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                        <div class="w-10 rounded-full">
                            <img alt="User Avatar"
                                src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                        </div>
                    </div>
                    <ul tabindex="0"
                        class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow-lg">
                        <li>
                            <form action="{{ route('auth.logout') }}" method="POST">
                                @csrf
                                <button type="submit">
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 bg-base-200 flex-grow">
                <!-- main -->
                @yield('content')
            </div>
        </div>
    </div>
</body>

</html>
