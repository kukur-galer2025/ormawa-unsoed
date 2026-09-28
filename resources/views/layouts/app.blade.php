<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Rekrutmen Ormawa UNSOED</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased selection:bg-blue-200 selection:text-blue-900" style="font-family: 'Plus Jakarta Sans', sans-serif;" x-data="{ sidebarOpen: window.innerWidth >= 1024 }" @resize.window="if(window.innerWidth >= 1024) { sidebarOpen = true } else { sidebarOpen = false }">
    <div class="flex h-screen w-full overflow-hidden">
        <!-- Sidebar Overlay (Mobile) -->
        <div x-show="sidebarOpen && window.innerWidth < 1024" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-black/50 lg:hidden" @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full w-0 lg:translate-x-0'" class="fixed inset-y-0 left-0 z-50 bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 transform transition-all duration-300 ease-in-out lg:sticky lg:top-0 lg:h-screen lg:z-40 flex flex-col shrink-0 overflow-hidden">
            <!-- Sidebar Header -->
            <div class="flex flex-col items-center justify-center px-6 py-8 border-b border-white/10 text-center">
                @php
                    $user = auth()->user();
                    $profilePic = null;
                    if ($user->role === 'mahasiswa' && $user->mahasiswaProfile && $user->mahasiswaProfile->foto) {
                        $profilePic = asset('storage/' . $user->mahasiswaProfile->foto);
                    } elseif ($user->google_avatar) {
                        $profilePic = $user->google_avatar;
                    }
                @endphp

                @if($profilePic)
                    <img src="{{ $profilePic }}" alt="Profile" class="w-20 h-20 rounded-full object-cover shadow-lg border-2 border-white/10 mb-3">
                @else
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white text-2xl font-bold shrink-0 shadow-lg border-2 border-white/10 mb-3">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div class="w-full">
                    <p class="text-white text-base font-semibold truncate">{{ $user->name }}</p>
                    <p class="text-blue-400/80 text-xs mt-1 capitalize font-medium">{{ $user->role }}</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                @yield('sidebar')
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-white/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-400 hover:text-white hover:bg-white/10 rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 relative h-screen overflow-y-auto overflow-x-hidden">

            <!-- Top Navbar -->
            @include('layouts.partials.navbar')

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-full">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.all.min.js" integrity="sha256-BpyIV7Y3e2pnqy8TQGXxsmOiQ4jXNDTOTBGL2TEJeDY=" crossorigin="anonymous"></script>
    <script>
        function confirmForm(event, message) {
            event.preventDefault();
            const form = event.target;
            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg',
                    cancelButton: 'rounded-lg'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
        
        function confirmClick(event, message) {
            event.preventDefault();
            const target = event.currentTarget;
            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg',
                    cancelButton: 'rounded-lg'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    if (target.tagName === 'BUTTON' && target.type === 'submit') {
                        target.closest('form').submit();
                    } else if (target.tagName === 'A') {
                        window.location.href = target.href;
                    }
                }
            });
        }

        // Flash messages sebagai popup
        @if(session('success'))
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                title: 'Berhasil!',
                text: {!! json_encode(session('success')) !!},
                icon: 'success',
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'OK',
                customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-lg' },
                timer: 4000,
                timerProgressBar: true,
            });
        });
        @endif

        @if(session('error'))
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                title: 'Gagal!',
                text: {!! json_encode(session('error')) !!},
                icon: 'error',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Tutup',
                customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-lg' },
            });
        });
        @endif

        @if(session('warning'))
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                title: 'Perhatian!',
                text: {!! json_encode(session('warning')) !!},
                icon: 'warning',
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'Tutup',
                customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-lg' },
            });
        });
        @endif

        @if(session('info'))
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                title: 'Informasi',
                text: {!! json_encode(session('info')) !!},
                icon: 'info',
                confirmButtonColor: '#3b82f6',
                confirmButtonText: 'Tutup',
                customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-lg' },
            });
        });
        @endif

        // Validation errors sebagai popup
        @if($errors->any())
        document.addEventListener('DOMContentLoaded', () => {
            const errorList = @json($errors->all());
            const errorHtml = errorList.map(e => `<li style="text-align:left;margin-bottom:4px;">• ${e}</li>`).join('');
            Swal.fire({
                title: 'Data Tidak Valid',
                html: `<ul style="font-size:14px;color:#374151;">${errorHtml}</ul>`,
                icon: 'error',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Perbaiki',
                customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-lg' },
            });
        });
        @endif
        // Global Anti-Double Submit
        document.addEventListener('submit', function (e) {
            const form = e.target;
            
            // Allow forms that have alpine preventDefault to be handled if they want, 
            // but we still mark them as submitted if they reach here.
            if (form.dataset.submitted === 'true') {
                e.preventDefault();
                return false;
            }
            
            // Find submit buttons
            const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
            
            // Check HTML5 validity first before disabling
            if (form.checkValidity && !form.checkValidity()) {
                return; // Let browser handle validation UI, don't disable button
            }

            form.dataset.submitted = 'true';
            
            submitButtons.forEach(btn => {
                setTimeout(() => {
                    btn.disabled = true;
                    if(btn.tagName === 'BUTTON') {
                        // Avoid overriding buttons that already have a loading state from Alpine
                        if (!btn.hasAttribute('x-bind:disabled') && !btn.hasAttribute(':disabled')) {
                            if(!btn.dataset.originalText) btn.dataset.originalText = btn.innerHTML;
                            btn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 inline-block text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;
                            btn.classList.add('opacity-75', 'cursor-wait');
                        }
                    }
                }, 10);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>