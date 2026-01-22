<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyEnergy | Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-full">

    <div class="flex min-h-full items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">

            <div class="bg-white p-8 shadow-sm rounded-xl border border-gray-100">
                <h2 class="mb-8 text-2xl font-bold tracking-tight text-gray-900">Sign in</h2>

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf

                    @if ($errors->any())
                    <div class="flex items-start p-4 text-amber-800 border border-amber-200 rounded-lg bg-amber-50">
                        <div style="width: 18px; height: 18px; background: #b45309; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 12px; flex-shrink: 0; font-size: 11px; margin-top: 2px;">!</div>
                        <div class="text-sm">
                            <ul class="list-none space-y-1">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif

                    <div class="space-y-4">
                        <div>
                            <label for="user_email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input id="user_email" name="user_email" type="email" value="{{ old('user_email') }}" required
                                class="block w-full rounded-lg border-gray-300 px-4 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm border-0">
                        </div>

                        <div>
                            <label for="user_password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input id="user_password" name="user_password" type="password" required
                                class="block w-full rounded-lg border-gray-300 px-4 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm border-0">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="flex w-full justify-center items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span>Sign in</span>
                            <span style="border: solid white; border-width: 0 2px 2px 0; display: inline-block; padding: 3px; transform: rotate(-45deg); margin-bottom: 1px;"></span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</body>

</html>