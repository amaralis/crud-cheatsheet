<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('assets/css/app.css')}}">
    <title>Bandwagon</title>
</head>

<body style="background-color:#dde7f773">
        <nav class="navbar navbar-expand-lg bg-body-secondary">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">BANDWAGON</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Collapsible wrapper -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left links -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                        <a class="nav-link" href="{{ route('bands.create') }}">Add Band</a>
                        </li>
                    </ul>
                    <!-- Left links -->
                </div>
                <!-- Collapsible wrapper -->

                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    
                    @guest
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                {{-- Os ternários abaixo devem ter um equivalente a um short circuit em javascript, para não precisar do retorno "else".
                                Pode-se fazer qualquer coisa com o operador null coalescing ou algo do género, mas o tempo para investigar é curto --}}
                                <a class="nav-link  {{ request()->is('login') ? 'active' : '' }}" aria-current="page" href="{{ route('login') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('register') ? 'active' : '' }}" href="{{ route('register') }}">Register</a>
                            </li>
                        </ul>                        
                    @endguest

                    @auth
                        <ul class="navbar-nav">
                            <li class="nav-item">

                                {{--
                                    Docs pobres. dump revela que auth()->guards()->user()->attributes() tem dados do utilizador em array. auth() é o helper da facade Auth
                                    Alternativamente, Auth::User()->getAttributes()['key'] para usar a classe GenericUser
                                    Optei aqui por usar helpers com auth()->user()->name
                                    --}}
                                <h5 class="m-0 me-5">Hi, {{auth()->user()->name}}</h5>
                            </li>
                        </ul>
                        {{-- cheeky styling with bootstrap here --}}
                        <form action="{{ route('logout') }}" method="POST" class="">
                            @csrf
                            <button type="submit" class="nav-link">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
        </nav>

        <div class="container mx-0 px-0 mx-sm-auto px-md-auto">
            @yield('content')
        </div>
        

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>