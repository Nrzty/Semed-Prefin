<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bem-vindo ao SIGED</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background-image: url("{{ asset('imgs/wallpaper.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body class="antialiased font-sans">
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 ">
    <div>
        <a href="/">
            <img src="{{ asset('imgs/logo2.png') }}" alt="Logo ARACAJU" class="h-20 w-auto">
        </a>
    </div>
    <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-xl overflow-hidden sm:rounded-lg">

        <h1 class="text-center text-2xl font-bold text-gray-700">
            Bem vindo ao <span class="text-blue-600">PREFIN</span>
        </h1>

        <div class="mt-6">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <h2 class="ml-2 font-semibold text-gray-600">Acessar portal do Prefin:</h2>
            </div>

            <form class="mt-4" method="POST" action="{{ route('login') }}">
                @csrf
                @error('email')
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 bg-gray-800 dark:text-red-400" role="alert">
                    <span class="font-medium">{{ $message }}</span>
                </div>
                @enderror

                @error('password')
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 bg-gray-800 dark:text-red-400" role="alert">
                    <span class="font-medium">{{ $message }}</span>
                </div>
                @enderror

                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                    </div>
                    <input id="email" type="email" name="email" placeholder="E-mail ou CPF" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div class="mt-4 relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <input id="password" type="password" name="password" placeholder="Senha" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full px-4 py-2 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Entrar
                    </button>
                </div>
            </form>
        </div>
        <hr class="my-6 border-gray-300">

        <div>
            <h2 class="text-center font-semibold text-gray-600">Acesse abaixo nosso material de suporte! 😉</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                <a href="#" class="flex items-center justify-center px-4 py-2 font-semibold text-white bg-blue-500 rounded-md hover:bg-blue-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m-9-8.994v6.494c0 1.252.607 2.41 1.636 3.095l.77.419m11.188-10.432v6.494c0 1.252-.607 2.41-1.636 3.095l-.77.419M12 6.253L5.636 3.095A2.25 2.25 0 003 5.013v6.494c0 1.252.607 2.41 1.636 3.095l.77.419M12 6.253L18.364 3.095A2.25 2.25 0 0121 5.013v6.494c0 1.252-.607 2.41-1.636 3.095l-.77.419"></path></svg>
                    Manuais
                </a>
                <a href="#" class="flex items-center justify-center px-4 py-2 font-semibold text-white bg-orange-500 rounded-md hover:bg-orange-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    FAQ
                </a>
                <a href="#" class="flex items-center justify-center px-4 py-2 font-semibold text-white bg-green-500 rounded-md hover:bg-green-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Dicas
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
