<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Pool 1.0</title>

		<link href="css/app.css" rel="stylesheet">
		@yield('head')
    </head>
	
    <body>
		<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
			<a class="navbar-brand" href="{{URL::route('home')}}">Pool 1.0</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarsExampleDefault">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item active">
						<a class="nav-link" href="{{ route('IN_DEVELOPMENT') }}">Упражнения</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ route('IN_DEVELOPMENT') }}">Упражнения подробно</a>
					</li>
                    <li class="nav-item ml-3">
						<a class="nav-link" href="{{ route('loader') }}">Загрузить</a>
					</li>
					<li class="nav-item mr-3">
						<a class="nav-link" href="{{ route('IN_DEVELOPMENT') }}">Загруженные файлы<span class="sr-only">(current)</span></a>
					</li>

                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
			</div>
		</nav>

		<main role="main" class="container" style="margin-top: 80px;">
			@yield('content')
		</main>
	
		<script src = "{{ asset('js/app.js') }}"></script>
		@yield('js')
    </body>
</html>