<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .sidebar {
                min-height: 100vh;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .sidebar .nav-link {
                color: rgba(255,255,255,0.8);
                padding: 0.75rem 1rem;
                border-radius: 0.5rem;
                margin: 0.25rem 0;
                transition: all 0.3s ease;
            }
            .sidebar .nav-link:hover,
            .sidebar .nav-link.active {
                color: white;
                background-color: rgba(255,255,255,0.1);
                transform: translateX(5px);
            }
            .main-content {
                background-color: #f8f9fa;
                min-height: 100vh;
            }
            .card {
                border: none;
                border-radius: 15px;
                box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
                transition: all 0.3s ease;
            }
            .card:hover {
                transform: translateY(-2px);
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            }
            .btn {
                border-radius: 10px;
                padding: 0.5rem 1.5rem;
                font-weight: 500;
                transition: all 0.3s ease;
            }
            .btn:hover {
                transform: translateY(-1px);
            }
            .navbar-brand {
                font-weight: 700;
                font-size: 1.5rem;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                    <div class="position-sticky pt-3">
                        <div class="text-center mb-4">
                            <h4 class="text-white fw-bold">
                                <i class="fas fa-boxes me-2"></i>
                                Controle de Estoque
                            </h4>
                        </div>
                        
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>
                                    Dashboard
                                </a>
                            </li>
                            
                            @if(auth()->user()->isAdministrador())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                    <i class="fas fa-users me-2"></i>
                                    Usuários
                                </a>
                            </li>
                            @endif
                            
                            @if(auth()->user()->isAdministrador() || auth()->user()->isGerenteEstoque())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}" href="{{ route('categorias.index') }}">
                                    <i class="fas fa-tags me-2"></i>
                                    Categorias
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('items.*') ? 'active' : '' }}" href="{{ route('items.index') }}">
                                    <i class="fas fa-box me-2"></i>
                                    Itens
                                </a>
                            </li>
                            @endif
                            
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('movimentacoes.*') ? 'active' : '' }}" href="{{ route('movimentacoes.index') }}">
                                    <i class="fas fa-exchange-alt me-2"></i>
                                    Movimentações
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('movimentacoes.baixa') ? 'active' : '' }}" href="{{ route('movimentacoes.baixa') }}">
                                    <i class="fas fa-minus-circle me-2"></i>
                                    Baixa de Itens
                                </a>
                            </li>
                            
                            @if(auth()->user()->isAdministrador() || auth()->user()->isGerenteEstoque())
                            <li class="nav-item">
                                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-white-50">
                                    <span>Relatórios</span>
                                </h6>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('relatorios.estoque-atual') ? 'active' : '' }}" href="{{ route('relatorios.estoque-atual') }}">
                                    <i class="fas fa-chart-bar me-2"></i>
                                    Estoque Atual
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('relatorios.historico-movimentacoes') ? 'active' : '' }}" href="{{ route('relatorios.historico-movimentacoes') }}">
                                    <i class="fas fa-history me-2"></i>
                                    Histórico
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('relatorios.estoque-baixo') ? 'active' : '' }}" href="{{ route('relatorios.estoque-baixo') }}">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Estoque Baixo
                                </a>
                            </li>
                            @endif
                        </ul>
                        
                        <hr class="my-3" style="border-color: rgba(255,255,255,0.2);">
                        
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user me-2"></i>
                                    Perfil
                                </a>
                            </li>
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                                        <i class="fas fa-sign-out-alt me-2"></i>
                                        Sair
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </nav>

                <!-- Main content -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">@yield('title', 'Dashboard')</h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <div class="btn-group me-2">
                                @yield('actions')
                            </div>
                        </div>
                    </div>

                    <!-- Alerts -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Page Content -->
                    @yield('content')
                </main>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        @yield('scripts')
    </body>
</html>
