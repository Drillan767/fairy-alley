<!doctype html>
<html data-theme="light" lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    @routes
    @vite
</head>
<body class="text-gray-600 body-font">
<header>
    <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center justify-between absolute top-0 left-0 right-0">
        <div class="flex items-center flex-shrink-0 text-white mr-6">
            <a href="/">
                <img src="/img/ADF-Logo.png" alt="logo" />
            </a>
        </div>
        <div>
            <a href="{{ route('login') }}"
               class="inline-flex items-center bg-gray-100 border-0 py-2 px-4 focus:outline-none hover:bg-gray-200 rounded text-base mt-4 md:mt-0">
                Accès membres
            </a>
        </div>
    </div>
</header>

<section>
    @yield('content')
</section>

<footer>
    <div class="container px-5 py-8 mx-auto text-center">
        <p class="text-sm text-gray-500 sm:ml-4 sm:mt-0 mt-4">
            © {{ date("Y") }} L'Allée des Fées
        </p>
    </div>
</footer>

@yield('javascript')

</body>
</html>
