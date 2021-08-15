<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>L'allée des fées</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @routes
    <script src="{{ mix('js/app.js') }}" defer></script>
    {{-- @vite --}}
</head>
<body class="text-gray-600 body-font">
<header>
    <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row justify-end">
        {{-- {{ route('login') }} --}}
        <a href="#"
           class="inline-flex items-center bg-gray-100 border-0 py-1 px-3 focus:outline-none hover:bg-gray-200 rounded text-base mt-4 md:mt-0">
            Connexion
        </a>
    </div>
    <div class="flex justify-center">
        <img src="/img/logo.png" class="w-1/2 md:w-1/4" alt="logo" />
    </div>
</header>
<section class="text-gray-600 body-font">
    <div class="container px-5 py-5 mx-auto flex flex-wrap">
        <div class="lg:w-1/2 w-full mb-10 lg:mb-0 rounded-lg overflow-hidden">
            <img alt="feature" class="object-cover object-center h-full w-full" src="https://picsum.photos/460/500?random=1">
        </div>
        <div class="flex flex-col flex-wrap lg:py-6 -mb-10 lg:w-1/2 lg:pl-12 lg:text-left text-center">
            <div class="p-4 flex flex-col items-center lg:items-start lg:flex-row">
                <div class="w-12 h-12 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-500 mb-4 flex-shrink-0">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-6 h-6" viewBox="0 0 24 24">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                    </svg>
                </div>

                <div class="flex-grow pl-0 lg:pl-6">
                    <h2 class="text-gray-900 text-lg title-font font-medium mb-2">Ouvert à tout le monde dans la bienveillance et l'harmonie</h2>
                    <p class="leading-relaxed text-base">
                        Cherchant à prendre principalement de leurs corps : physique, émotionnel et mental ainsi que d'approfondir leur intériorisation, centré sur leur être essentiel
                    </p>
                    <a class="mt-3 text-indigo-500 inline-flex items-center">En savoir plus
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="p-4 flex flex-col items-center lg:items-start lg:flex-row">
                <div class="w-12 h-12 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-500 mb-4 flex-shrink-0">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-6 h-6" viewBox="0 0 24 24">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                    </svg>
                </div>
                <div class="flex-grow pl-0 lg:pl-6">
                    <h2 class="text-gray-900 text-lg title-font font-medium mb-2">Dans un lieu préservé</h2>
                    <p class="leading-relaxed text-base">
                        Conçu pour le ressourcement,  protégé de toute onde électromagnétique.
                    </p>
                    <a class="mt-3 text-indigo-500 inline-flex items-center">En savoir plus
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="p-4 flex flex-col items-center lg:items-start lg:flex-row">
                <div class="w-12 h-12 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-500 mb-4 flex-shrink-0">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-6 h-6" viewBox="0 0 24 24">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                    </svg>
                </div>
                <div class="flex-grow pl-0 lg:pl-6">
                    <h2 class="text-gray-900 text-lg title-font font-medium mb-2">Un vrai Retour à La Source©</h2>
                    <p class="leading-relaxed text-base">
                        Dans un centre avant-gardiste pour la qualité de l'eau grâce au Biodynamiseur®
                    </p>
                    <a class="mt-3 text-indigo-500 inline-flex items-center">En savoir plus
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="p-4 flex flex-col items-center lg:items-start lg:flex-row">
                <div class="w-12 h-12 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-500 mb-4 flex-shrink-0">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-6 h-6" viewBox="0 0 24 24">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                    </svg>
                </div>
                <div class="flex-grow pl-0 lg:pl-6">
                    <h2 class="text-gray-900 text-lg title-font font-medium mb-2">Massages ayurvédiques</h2>
                    <p class="leading-relaxed text-base">
                        Samvahan & gymnastique posturale globale
                    </p>
                    <a class="mt-3 text-indigo-500 inline-flex items-center">En savoir plus
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="text-gray-600 body-font">
    <div class="container px-5 py-5 mx-auto flex flex-wrap">
        <div class="flex flex-col flex-wrap lg:py-6 -mb-10 lg:w-1/2 lg:pl-12 lg:text-left text-center">
            <div class="p-4 flex flex-col items-center lg:items-start lg:flex-row">
                <div class="w-12 h-12 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-500 mb-4 flex-shrink-0">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-6 h-6" viewBox="0 0 24 24">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                    </svg>
                </div>
                <div class="flex-grow pl-0 lg:pl-6">
                    <h2 class="text-gray-900 text-lg title-font font-medium mb-2">Bol d'air Jacquier</h2>
                    <p class="leading-relaxed text-base">Gastropub indxgo juice poutine, ramps microdosing banh mi pug VHS try-hard ugh iceland kickstarter tumblr live-edge tilde</p>
                    <a class="mt-3 text-indigo-500 inline-flex items-center">En savoir plus
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="p-4 flex flex-col items-center lg:items-start lg:flex-row">
                <div class="w-12 h-12 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-500 mb-4 flex-shrink-0">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-6 h-6" viewBox="0 0 24 24">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                    </svg>
                </div>
                <div class="flex-grow pl-0 lg:pl-6">
                    <h2 class="text-gray-900 text-lg title-font font-medium mb-2">Luminothérapie</h2>
                    <p class="leading-relaxed text-base">
                        Pour rééquilibrer entre autres la sécrétion de mélatonine/sérotonine
                    </p>
                    <a class="mt-3 text-indigo-500 inline-flex items-center">En savoir plus
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="p-4 flex flex-col items-center lg:items-start lg:flex-row">
                <div class="w-12 h-12 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-500 mb-4 flex-shrink-0">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-6 h-6" viewBox="0 0 24 24">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                    </svg>
                </div>
                <div class="flex-grow pl-0 lg:pl-6">
                    <h2 class="text-gray-900 text-lg title-font font-medium mb-2">Géoécologie</h2>
                    <p class="leading-relaxed text-base">Gastropub indxgo juice poutine, ramps microdosing banh mi pug VHS try-hard ugh iceland kickstarter tumblr live-edge tilde</p>
                    <a class="mt-3 text-indigo-500 inline-flex items-center">En savoir plus
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="p-4 flex flex-col items-center lg:items-start lg:flex-row">
                <div class="w-12 h-12 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-500 mb-4 flex-shrink-0">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-6 h-6" viewBox="0 0 24 24">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                    </svg>
                </div>
                <div class="flex-grow pl-0 lg:pl-6">
                    <h2 class="text-gray-900 text-lg title-font font-medium mb-2">Souscrivez à notre abonnement</h2>
                    <p class="leading-relaxed text-base">
                        Accédez à nos ateliers en libre service
                    </p>
                    <a class="mt-3 text-indigo-500 inline-flex items-center">En savoir plus
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="lg:w-1/2 w-full mb-10 lg:mb-0 rounded-lg overflow-hidden">
            <img alt="feature" class="object-cover object-center h-full w-full" src="https://picsum.photos/460/500?random=2">
        </div>

    </div>
</section>
<section class="text-gray-600 body-font">
    <div class="container px-5 py-5 mx-auto flex flex-wrap">
        <div class="lg:w-1/2 w-full mb-10 lg:mb-0 rounded-lg overflow-hidden">
            <img alt="feature" class="object-cover object-center h-full w-full" src="https://picsum.photos/460/500?random=3">
        </div>
        <div class="flex flex-col flex-wrap lg:py-6 -mb-10 lg:w-1/2 lg:pl-12 lg:text-left text-center">

            <div class="p-4 flex flex-col items-center lg:items-start lg:flex-row">
                <div class="w-12 h-12 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-500 mb-4 flex-shrink-0">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-6 h-6" viewBox="0 0 24 24">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                    </svg>
                </div>
                <div class="flex-grow pl-0 lg:pl-6">
                    <h2 class="text-gray-900 text-lg title-font font-medium mb-2">Thérapie florale</h2>
                    <p class="leading-relaxed text-base">
                        Avec les élixirs floraux du bush australien
                    </p>
                    <a class="mt-3 text-indigo-500 inline-flex items-center">En savoir plus
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="p-4 flex flex-col items-center lg:items-start lg:flex-row">
                <div class="w-12 h-12 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-500 mb-4 flex-shrink-0">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-6 h-6" viewBox="0 0 24 24">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                    </svg>
                </div>
                <div class="flex-grow pl-0 lg:pl-6">
                    <h2 class="text-gray-900 text-lg title-font font-medium mb-2">Conférences régulières</h2>
                    <p class="leading-relaxed text-base">
                        La qualité de l’eau, le plasma marin de Quinton, les élixirs floraux, la méditation, la musique des plantes, les procédés pour assainir une maison d’habitation, etc.
                    </p>
                    <a class="mt-3 text-indigo-500 inline-flex items-center">En savoir plus
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="p-4 flex flex-col items-center lg:items-start lg:flex-row">
                <div class="w-12 h-12 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-500 mb-4 flex-shrink-0">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-6 h-6" viewBox="0 0 24 24">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                    </svg>
                </div>
                <div class="flex-grow pl-0 lg:pl-6">
                    <h2 class="text-gray-900 text-lg title-font font-medium mb-2">Accompagnement en coaching de vie</h2>
                    <p class="leading-relaxed text-base">
                        Personnellement ou professionnellement
                    </p>
                    <a class="mt-3 text-indigo-500 inline-flex items-center">En savoir plus
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
</body>
</html>
