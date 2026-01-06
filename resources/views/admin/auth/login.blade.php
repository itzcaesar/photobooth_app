@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center min-h-[calc(100vh-200px)]">
        <div class="w-full max-w-md">

            <!-- Glass Card -->
            <div class="glass-card p-8 md:p-10 relative overflow-hidden ring-1 ring-cyan-500/30">

                <!-- Decorative Glow [Admin Special] -->
                <div
                    class="absolute -top-20 -right-20 w-40 h-40 bg-cyan-500 rounded-full blur-3xl opacity-20 pointer-events-none">
                </div>
                <div
                    class="absolute -bottom-20 -left-20 w-40 h-40 bg-blue-600 rounded-full blur-3xl opacity-20 pointer-events-none">
                </div>

                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-cyan-500/10 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-[Poppins] font-bold text-white">Admin Portal</h3>
                    <p class="text-gray-400 text-xs uppercase tracking-widest mt-1">Authorized Access Only</p>
                </div>

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 flex items-start">
                        <svg class="h-5 w-5 text-red-400 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-red-300 text-sm">{{ session('error') }}</span>
                    </div>
                @endif

                <form action="/admin/login" method="POST" class="space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-300 ml-1">Email Address</label>
                        <input type="email" name="email"
                            class="w-full px-5 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                            placeholder="admin@example.com">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-300 ml-1">Password</label>
                        <input type="password" name="password"
                            class="w-full px-5 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                            placeholder="••••••••">
                    </div>

                    <button
                        class="w-full py-3.5 rounded-xl font-bold text-white shadow-lg bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 transform hover:-translate-y-1 transition-all duration-200">
                        Login to Dashboard
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection