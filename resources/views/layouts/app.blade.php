<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sanando Almas — @yield('titulo')</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Flowbite --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --azul-oscuro:  #010e6b;
            --morado-medio: #703b94;
            --morado-claro: #be74be;
            --morado-rosa:  #d481d2;
            --texto-blanco: #ffffff;
            --texto-suave:  #e2d9f3;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #f3f0f8;
        }

        /* ── Navbar ─────────────────────────────────────────────── */
        #navbar {
            background: var(--azul-oscuro);
            border-bottom: 1px solid rgba(190, 116, 190, 0.25);
        }

        .brand-name {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            color: var(--texto-blanco);
        }

        #sidebar-mobile-btn {
            color: white;
            background: transparent;
            border: 1px solid rgba(190, 116, 190, 0.4);
            border-radius: 7px;
            padding: 6px;
            transition: background .15s, color .15s;
        }
        #sidebar-mobile-btn:hover {
            background: var(--morado-claro);
            color: var(--azul-oscuro);
        }

        #dropdown-user {
            background: var(--morado-medio);
            border: 1px solid rgba(190, 116, 190, 0.35);
            border-radius: 12px;
        }
        #dropdown-user .user-name  { color: var(--texto-blanco); font-weight: 600; }
        #dropdown-user .user-email { color: var(--texto-suave); font-size: .8rem; }
        #dropdown-user a {
            color: var(--texto-blanco);
            border-radius: 6px;
            transition: background .15s;
        }
        #dropdown-user a:hover {
            background: var(--azul-oscuro);
        }

        /* ── Sidebar ─────────────────────────────────────────────── */
        #sidebar {
            background: var(--azul-oscuro);
            border-right: 1px solid rgba(190, 116, 190, 0.2);
        }

        #sidebar .section-label {
            color: rgba(190, 116, 190, 0.6);
            font-size: .7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: 0 .5rem;
            margin-bottom: .35rem;
        }

        #sidebar a {
            color: var(--texto-suave);
            border-radius: 8px;
            transition: background .18s, color .18s;
            font-size: .875rem;
            font-weight: 500;
        }
        #sidebar a:hover {
            background: var(--morado-claro);
            color: var(--azul-oscuro);
        }
        #sidebar a.active {
            background: var(--morado-medio);
            color: var(--texto-blanco);
        }

        .sidebar-badge {
            background: var(--morado-rosa);
            color: #2d004a;
            font-size: .68rem;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: 99px;
        }

        #sidebar-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        #sidebar-footer .footer-name  { color: var(--texto-blanco); font-size: .8rem; font-weight: 600; }
        #sidebar-footer .footer-role  { color: var(--texto-suave);  font-size: .7rem; }
        #sidebar-footer img {
            border: 2px solid var(--morado-claro);
        }

        /* Botón salir sidebar */
        .btn-salir {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .625rem .75rem;
            width: 100%;
            text-align: left;
            color: var(--texto-suave);
            border-radius: 8px;
            transition: background .18s, color .18s;
            font-size: .875rem;
            font-weight: 500;
            background: transparent;
            border: none;
            cursor: pointer;
        }
        .btn-salir:hover {
            background: var(--morado-claro);
            color: var(--azul-oscuro);
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-100">

    @php
        //se busca el modelo la foto de perfil
        $psicologoGlobal = \App\Models\Psicologo::find(session('id_psicologa'));
        
        $fotoPerfil = $psicologoGlobal ? $psicologoGlobal->url_imagen : asset('assets/iconos/perfil_psicologa.jpg');
    @endphp

    {{-- ═══════════ NAVBAR ═══════════ --}}
    <nav id="navbar" class="fixed top-0 z-50 w-full">
        <div class="px-4 py-3 flex items-center justify-between">

            <div class="flex items-center gap-3">
                <button id="sidebar-mobile-btn" class="sm:hidden" aria-label="Abrir menú lateral">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h10"/>
                    </svg>
                </button>

                <a href="{{ url('/inicio') }}" class="flex items-center gap-2.5">
                    <div style="width:32px;height:32px;background:var(--morado-claro);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <span class="brand-name text-base tracking-wide hidden sm:inline">Sanando Almas</span>
                </a>
            </div>

            <div class="flex items-center gap-3">

                {{-- Campana --}}
                <button class="relative p-2 text-white/70 hover:text-white rounded-lg hover:bg-white/10 transition" aria-label="Notificaciones">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full" style="background:var(--morado-rosa);"></span>
                </button>

                {{-- Avatar + dropdown --}}
                <div class="relative">
                    <button type="button"
                        data-dropdown-toggle="dropdown-user"
                        class="flex items-center gap-2 rounded-full focus:outline-none"
                        aria-expanded="false"
                        aria-haspopup="true">
                        

<img class="w-8 h-8 rounded-full border-2 object-cover"
     style="border-color:var(--morado-claro);"
     src="{{ $psicologoGlobal && $psicologoGlobal->url_imagen ? $psicologoGlobal->url_imagen : asset('assets/iconos/perfil_psicologa.jpg') }}"
     alt="Foto de perfil">




                        <span class="hidden md:block text-sm text-white font-medium">
                            {{ session('usuario') ?? 'Usuario' }}
                        </span>
                        <svg class="w-3.5 h-3.5 text-white/60 hidden md:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div id="dropdown-user"
                        class="z-50 hidden absolute right-0 mt-2 w-48 shadow-xl py-1 overflow-hidden"
                        role="menu">
                        <div class="px-4 py-3 border-b border-white/10">
                            <p class="user-name text-sm">{{ session('usuario') ?? 'Usuario' }}</p>
                            <p class="user-email mt-0.5 truncate">{{ session('correo') ?? 'usuario@ejemplo.com' }}</p>
                        </div>
                        <ul class="p-1.5 text-sm">
                            <li>
                                <a href="{{ url('/inicio') }}" class="flex items-center gap-2 px-3 py-2" role="menuitem">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/>
                                    </svg>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('psicologos.index') }}" class="flex items-center gap-2 px-3 py-2" role="menuitem">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Mi perfil
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="/logout">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 px-3 py-2 w-full text-left text-white hover:bg-[#010e6b] rounded-md transition" role="menuitem">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Salir
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- ═══════════ SIDEBAR ═══════════ --}}
    <aside id="sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-full transition-transform -translate-x-full sm:translate-x-0 pt-16"
        aria-label="Menú lateral">
        <div class="h-full px-3 py-5 overflow-y-auto flex flex-col">

            <p class="section-label">Menú</p>
            <ul class="space-y-1 mb-4">
                <li>
                    <a href="{{ url('/inicio') }}"
                    class="flex items-center gap-3 px-3 py-2.5 {{ request()->is('inicio') ? 'active' : '' }}">
                         <img src="{{ asset('assets/iconos/principal.png') }}" class="w-5 h-5 shrink-0" alt="Inicio">
                        Principal
                    </a>
                </li>
                <li>
                    <a href="{{ route('expedientes.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 {{ request()->is('expedientes*') ? 'active' : '' }}">
                         <img src="{{ asset('assets/iconos/expedientes.png') }}" class="w-5 h-5 shrink-0" alt="Expedientes">
                        Expedientes
                    </a>
                </li>
                <li>
                    <a href="/pacientes"   
                    class="flex items-center gap-3 px-3 py-2.5 {{ request()->is('pacientes*') ? 'active' : '' }}">
                         <img src="{{ asset('assets/iconos/pacientes.png') }}" class="w-5 h-5 shrink-0" alt="Pacientes">
                        Pacientes
                    </a>
                </li>
                <li>
                    <a href="/citas"
                    class="flex items-center gap-3 px-3 py-2.5 {{ request()->is('citas*') ? 'active' : '' }}">
                         <img src="{{ asset('assets/iconos/citas.png') }}" class="w-5 h-5 shrink-0" alt="Citas">
                        Citas
                    </a>
                </li>
                <li>
                    <a href="/notificaciones"
                    class="flex items-center gap-3 px-3 py-2.5 {{ request()->is('notificaciones*') ? 'active' : '' }}">
                         <img src="{{ asset('assets/iconos/notificaciones.png') }}" class="w-5 h-5 shrink-0" alt="Notificaciones">
                        Notificaciones
                    </a>
                </li>
            </ul>

            <div class="border-t border-white/10 mb-4"></div>

            <ul class="space-y-1">
                <li>
                    <a href="{{ route('psicologos.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 {{ request()->is('psicologos*') ? 'active' : '' }}">
                         <img src="{{ asset('assets/iconos/cuenta.png') }}" class="w-5 h-5 shrink-0" alt="Perfil">
                        Mi perfil
                    </a>
                </li>
                <li>
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="btn-salir">
                            <img src="{{ asset('assets/iconos/salir.png') }}" class="w-5 h-5 shrink-0" alt="Salir">
                            Salir
                        </button>
                    </form>
                </li>
            </ul> 

            {{-- Footer sidebar --}}
            <div id="sidebar-footer" class="mt-auto pt-4">
                <div class="flex items-center gap-3 px-2">
<img class="w-8 h-8 rounded-full border-2 object-cover"
     style="border-color:var(--morado-claro);"
     src="{{ $psicologoGlobal && $psicologoGlobal->url_imagen ? $psicologoGlobal->url_imagen : asset('assets/iconos/perfil_psicologa.jpg') }}"
     alt="Foto de perfil">                    <div class="overflow-hidden">
                        <p class="footer-name truncate">{{ session('usuario') ?? 'Usuario' }}</p>
                        <p class="footer-role truncate">Psicólogo</p>
                    </div>
                </div>
            </div>

        </div>
    </aside>

    {{-- ═══════════ CONTENIDO ═══════════ --}}
    <main class="sm:ml-64 pt-20 min-h-screen" style="background:#f3f0f8;">
        @yield('contenido')
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

    <script>
        // Toggle sidebar mobile
        const sidebarBtn = document.getElementById('sidebar-mobile-btn');
        const sidebar    = document.getElementById('sidebar');
        if (sidebarBtn && sidebar) {
            sidebarBtn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }
    </script>

    @stack('scripts')

</body>
</html>