<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CMS Login | Admin Portal</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS Vite asset integration -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1 { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 flex items-center justify-center min-h-screen relative px-6 overflow-hidden">
    
    <!-- Glowing background lights -->
    <div class="absolute w-[40rem] h-[40rem] bg-cyan-900/10 rounded-full blur-[140px] pointer-events-none -z-10"></div>

    <div class="w-full max-w-md bg-slate-900 border border-slate-850 p-8 sm:p-10 rounded-3xl backdrop-blur-2xl shadow-2xl relative">
        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-transparent rounded-3xl pointer-events-none"></div>

        <!-- Header Info -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-extrabold text-white tracking-tight mb-2">CMS Login</h1>
            <p class="text-xs text-slate-400 font-mono">Authenticate to manage your web portfolio</p>
        </div>

        <!-- Form submissions -->
        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Global Auth Validation errors -->
            @error('auth')
                <div class="p-3.5 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-xl text-xs font-semibold">
                    {{ $message }}
                </div>
            @enderror

            <!-- Username Field -->
            <div>
                <label for="username" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Username</label>
                <input type="text" 
                       name="username" 
                       id="username" 
                       required 
                       autofocus
                       class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none focus:ring-1 focus:ring-cyan-500/20 transition-all duration-200">
                @error('username')
                    <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Password</label>
                <input type="password" 
                       name="password" 
                       id="password" 
                       required 
                       class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none focus:ring-1 focus:ring-cyan-500/20 transition-all duration-200">
                @error('password')
                    <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Button -->
            <button type="submit" 
                    class="w-full py-3.5 bg-gradient-to-r from-cyan-500 to-indigo-500 hover:from-cyan-600 hover:to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-cyan-500/10 hover:shadow-cyan-500/20 active:scale-[0.98] transition-all duration-200">
                Sign In
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="{{ route('portfolio.index') }}" class="text-xs text-slate-500 hover:text-slate-300 transition-colors duration-200">
                &larr; Back to visitor website
            </a>
        </div>
    </div>

</body>
</html>
