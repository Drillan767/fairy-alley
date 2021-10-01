<!doctype html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $page->title }} | L'allée des Fées</title>
    @vite
    @routes
</head>
<body>
    <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row justify-between absolute top-0 left-0 right-0">
        <img src="http://localhost:8080/img/logo.png" class="h-10 w-10" alt="logo" />
        <h1 class="sm:text-text-xl text-3xl font-medium title-font mb-2 text-gray-900 text-center">
            L'Allée des Fées
        </h1>
        <a href="{{ route('login') }}"
           class="inline-flex items-center bg-gray-100 border-0 py-1 px-3 focus:outline-none hover:bg-gray-200 rounded text-base mt-4 md:mt-0">
            Accès membres
        </a>
    </div>
    <div style="background-image: url({{ $page->illustration }})" class="h-50vh bg-center"></div>
    <div class="container max-w-5xl mx-auto -mt-32">
        <div class="bg-white w-full p-8 md:p-24 text-xl md:text-2xl text-gray-800 leading-normal">
            <h2 class="text-center font-bold break-normal text-3xl md:text-5xl mb-8">
                {{ $page->title }}
            </h2>

            <div class="prose max-w-none">
                {!! $page->content !!}
            </div>
        </div>
    </div>



</body>
</html>
