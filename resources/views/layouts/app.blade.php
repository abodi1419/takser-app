<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __(config('app.name', 'Laravel')) }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('js/jquery-3.6.0.js') }}"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @if(app()->getLocale()=='ar')
        <link href="{{ asset('css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    @endif


    <link href="{{ asset('css/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    {{--    @vite(['resources/sass/app.scss', 'resources/js/app.js'])--}}
</head>
<body>
    <div id="app" @if(app()->getLocale()=='ar') dir="rtl" @endif>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ __(config('app.name', 'Laravel')) }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <div class="dropdown">
                                <a id="groupsDropdown" href="#" class="dropdown-toggle nav-link" role="button" data-bs-toggle="dropdown">
                                    {{__('Groups')}}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="groupsDropdown">
                                    <a href= "{{route('groups.index')}}" class="dropdown-item">{{__('Groups')}}</a>
                                    <a href= "{{route('groups.create')}}" class="dropdown-item">{{__('Create group')}}</a>

                                </div>
                            </div>

                        </li>
                        <li class="nav-item">
                            <div class="dropdown">
                                <a id="tasksDropdown" href="#" class="dropdown-toggle nav-link" role="button" data-bs-toggle="dropdown">
                                    {{__('Tasks')}}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="tasksDropdown">
                                    <a href= "{{route('tasks.index')}}" class="dropdown-item">{{__('Tasks')}}</a>
                                    <a href= "{{route('tasks.create')}}" class="dropdown-item">{{__('Create task')}}</a>

                                </div>
                            </div>

                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('user.edit') }}">
                                        {{__("Account")}}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{session()->get('message')}}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
