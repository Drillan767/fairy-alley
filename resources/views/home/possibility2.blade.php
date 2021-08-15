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
    <div class="container px-5 py-24 mx-auto">
        <div class="flex flex-wrap w-full mb-20">
            <div class="lg:w-1/2 w-full mb-6 lg:mb-0">
                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-2 text-gray-900">Pitchfork Kickstarter Taxidermy</h1>
                <div class="h-1 w-20 bg-indigo-500 rounded"></div>
            </div>
            <p class="lg:w-1/2 w-full leading-relaxed text-gray-500">Whatever cardigan tote bag tumblr hexagon brooklyn asymmetrical gentrify, subway tile poke farm-to-table. Franzen you probably haven't heard of them man bun deep jianbing selfies heirloom prism food truck ugh squid celiac humblebrag.</p>
        </div>

        <div id="macy">
            <div>
                <div class="bg-gray-100 p-6 rounded-lg">
                    <img class="h-40 rounded w-full object-cover object-center mb-6" src="https://picsum.photos/720/400?random=1" alt="content">
                    <h2 class="text-lg text-gray-900 font-medium title-font mb-4">Ouvert à tout le monde dans la bienveillance et l'harmonie</h2>
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

            <div>
                <div class="bg-gray-100 p-6 rounded-lg">
                    <img class="h-40 rounded w-full object-cover object-center mb-6" src="https://picsum.photos/720/400?random=2" alt="content">
                    <h2 class="text-lg text-gray-900 font-medium title-font mb-4">Dans un lieu préservé</h2>
                    <p class="leading-relaxed text-base">
                        Conçu pour le ressourcement, protégé de toute onde électromagnétique.
                    </p>
                    <a class="mt-3 text-indigo-500 inline-flex items-center">En savoir plus
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div>
                <div class="bg-gray-100 p-6 rounded-lg">
                    <img class="h-40 rounded w-full object-cover object-center mb-6" src="https://picsum.photos/720/400?random=3" alt="content">
                    <h2 class="text-lg text-gray-900 font-medium title-font mb-4">Un vrai Retour à La Source©</h2>
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

            <div>
                <div class="bg-gray-100 p-6 rounded-lg">
                    <img class="h-40 rounded w-full object-cover object-center mb-6" src="https://picsum.photos/720/400?random=4" alt="content">
                    <h2 class="text-lg text-gray-900 font-medium title-font mb-4">Massages ayurvédiques</h2>
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

            <div>
                <div class="bg-gray-100 p-6 rounded-lg">
                    <img class="h-40 rounded w-full object-cover object-center mb-6" src="https://picsum.photos/720/400?random=5" alt="content">
                    <h2 class="text-lg text-gray-900 font-medium title-font mb-4">Bol d'air Jacquier</h2>
                    <p class="leading-relaxed text-base">
                        Gastropub indxgo juice poutine, ramps microdosing banh mi pug VHS try-hard ugh iceland kickstarter tumblr live-edge tilde
                    </p>
                    <a class="mt-3 text-indigo-500 inline-flex items-center">En savoir plus
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div>
                <div class="bg-gray-100 p-6 rounded-lg">
                    <img class="h-40 rounded w-full object-cover object-center mb-6" src="https://picsum.photos/720/400?random=6" alt="content">
                    <h2 class="text-lg text-gray-900 font-medium title-font mb-4">Luminothérapie</h2>
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
            <div>
                <div class="bg-gray-100 p-6 rounded-lg">
                    <img class="h-40 rounded w-full object-cover object-center mb-6" src="https://picsum.photos/720/400?random=7" alt="content">
                    <h2 class="text-lg text-gray-900 font-medium title-font mb-4">Géoécologie</h2>
                    <p class="leading-relaxed text-base">
                        Gastropub indxgo juice poutine, ramps microdosing banh mi pug VHS try-hard ugh iceland kickstarter tumblr live-edge tilde
                    </p>
                    <a class="mt-3 text-indigo-500 inline-flex items-center">En savoir plus
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div>
                <div class="bg-gray-100 p-6 rounded-lg">
                    <img class="h-40 rounded w-full object-cover object-center mb-6" src="https://picsum.photos/720/400?random=8" alt="content">
                    <h2 class="text-lg text-gray-900 font-medium title-font mb-4">
                        Souscrivez à notre abonnement
                    </h2>
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
            <div>
                <div class="bg-gray-100 p-6 rounded-lg">
                    <img class="h-40 rounded w-full object-cover object-center mb-6" src="https://picsum.photos/720/400?random=9" alt="content">
                    <h2 class="text-lg text-gray-900 font-medium title-font mb-4">
                        Thérapie florale
                    </h2>
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
            <div>
                <div class="bg-gray-100 p-6 rounded-lg">
                    <img class="h-40 rounded w-full object-cover object-center mb-6" src="https://picsum.photos/720/400?random=10" alt="content">
                    <h2 class="text-lg text-gray-900 font-medium title-font mb-4">
                        Conférences régulières
                    </h2>
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
            <div>
                <div class="bg-gray-100 p-6 rounded-lg">
                    <img class="h-40 rounded w-full object-cover object-center mb-6" src="https://picsum.photos/720/400?random=11" alt="content">
                    <h2 class="text-lg text-gray-900 font-medium title-font mb-4">
                        Accompagnement en coaching de vie
                    </h2>
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
