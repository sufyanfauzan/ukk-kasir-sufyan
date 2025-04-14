<!DOCTYPE html>
<html data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('resources/css/app.css')
</head>

<body>
    <div class="relative flex flex-col justify-center h-screen overflow-hidden">
        <div class="w-full p-5 m-auto rounded-md shadow-md ring-2 ring-gray-800/50 lg:max-w-lg">
            <h1 class="text-3xl font-semibold text-center text-gray-700">Login</h1>

            @if ($errors->any())
                <p style="color: red;">{{ $errors->first() }}</p>
            @endif

            <form class="space-y-4" action="{{ route('auth.login.process') }}" method="POST">
                @csrf
                <div>
                    <label class="label">
                        <span class="text-base label-text">Email</span>
                    </label>
                    <input type="email" name="email" class="w-full input input-bordered mt-2" />
                </div>
                <div>
                    <label class="label">
                        <span class="text-base label-text">Password</span>
                    </label>
                    <input type="password" name="password" class="w-full input input-bordered mt-2" />
                </div>
                <div>
                    <button type="submit" class="btn-neutral btn btn-block">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
