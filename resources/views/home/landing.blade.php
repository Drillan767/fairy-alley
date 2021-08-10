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
<body>
<header class="text-gray-600 body-font">
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
    <div class="container px-5 py-10 mx-auto">
        <div class="flex flex-wrap w-full mb-5 flex-col items-center text-center">
            <h1 class="sm:text-3xl text-2xl font-medium title-font mb-2 text-gray-900">
                L'Allée des Fées
            </h1>
            <p class="lg:w-1/2 w-full leading-relaxed text-gray-500">
                Séverine & Thierry vous proposent un accompagnement bien-être
            </p>
        </div>
        <ul class="list-disc list-inside leading-relaxed mb-6">
            <li class="mb-2">
                Ouvert à celles et ceux qui cherchent, dans la bienveillance et l'harmonie, à
                <ul class="list-inside ml-2">
                    <li class="mb-2">- <b>prendre soin principalement de leurs corps :</b> physique, émotionnel et mental</li>
                    <li class="mb-2">- <b>approfondir leur intériorisation</b>, centré sur leur être essentiel </li>
                </ul>
            </li>
            <li class="mb-2">Dans un <b>lieu préservé</b>, conçu pour le ressourcement,  protégé de toute onde électromagnétique.</li>
            <li class="mb-2">Dans <b>un centre avant-gardiste pour la qualité de l'eau grâce au Biodynamiseur®</b> : Un vrai Retour à La Source© !</li>
            <li class="mb-2"><b>Massages ayurvédiques</b> Samvahan & gymnastique posturale globale</li>
            <li class="mb-2">Accompagnements en <b>coaching professionnel ou de vie</b></li>
            <li class="mb-2"><b>Thérapie florale</b> avec les élixirs floraux du bush australien</li>
            <li class="mb-2"><b>Bol d'air Jacquier</b></li>
            <li class="mb-2"><b>Luminothérapie</b> (pour rééquilibrer entre autres la sécrétion de mélatonine/sérotonine)</li>
            <li class="mb-2"><b>Méditation</b></li>
            <li class="mb-2"><b>Géoécologie</b></li>
            <li class="mb-2"><b>Ateliers en libre-service</b> avec abonnement </li>
            <li class="mb-2">
                <b>Conférences</b> régulières sur la qualité de l’eau, le plasma marin de Quinton, les élixirs floraux, la méditation,
                la musique des plantes, les procédés pour assainir une maison d’habitation, etc.
            </li>
        </ul>
        <div class="flex flex-wrap -m-4">
            <div class="xl:w-1/3 md:w-1/2 p-4">

                <div class="border border-gray-200 p-6 rounded-lg">
                    <div class="flex justify-center">
                        <div
                            class="w-10 h-10 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-500 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-lg text-gray-900 font-medium title-font mb-2">Shooting Stars</h2>
                    <p class="leading-relaxed text-base">Fingerstache flexitarian street art 8-bit waist co, subway tile
                        poke farm.</p>
                </div>
            </div>
            <div class="xl:w-1/3 md:w-1/2 p-4">
                <div class="border border-gray-200 p-6 rounded-lg">
                    <div
                        class="w-10 h-10 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-500 mb-4">
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                             stroke-width="2" class="w-6 h-6" viewBox="0 0 24 24">
                            <circle cx="6" cy="6" r="3"></circle>
                            <circle cx="6" cy="18" r="3"></circle>
                            <path d="M20 4L8.12 15.88M14.47 14.48L20 20M8.12 8.12L12 12"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg text-gray-900 font-medium title-font mb-2">The Catalyzer</h2>
                    <p class="leading-relaxed text-base">Fingerstache flexitarian street art 8-bit waist co, subway tile
                        poke farm.</p>
                </div>
            </div>
            <div class="xl:w-1/3 md:w-1/2 p-4">
                <div class="border border-gray-200 p-6 rounded-lg">
                    <div
                        class="w-10 h-10 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-500 mb-4">
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                             stroke-width="2" class="w-6 h-6" viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <h2 class="text-lg text-gray-900 font-medium title-font mb-2">Neptune</h2>
                    <p class="leading-relaxed text-base">Fingerstache flexitarian street art 8-bit waist co, subway tile
                        poke farm.</p>
                </div>
            </div>
            <div class="xl:w-1/3 md:w-1/2 p-4">
                <div class="border border-gray-200 p-6 rounded-lg">
                    <div
                        class="w-10 h-10 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-500 mb-4">
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                             stroke-width="2" class="w-6 h-6" viewBox="0 0 24 24">
                            <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1zM4 22v-7"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg text-gray-900 font-medium title-font mb-2">Melanchole</h2>
                    <p class="leading-relaxed text-base">Fingerstache flexitarian street art 8-bit waist co, subway tile
                        poke farm.</p>
                </div>
            </div>
            <div class="xl:w-1/3 md:w-1/2 p-4">
                <div class="border border-gray-200 p-6 rounded-lg">
                    <div
                        class="w-10 h-10 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-500 mb-4">
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                             stroke-width="2" class="w-6 h-6" viewBox="0 0 24 24">
                            <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg text-gray-900 font-medium title-font mb-2">Bunker</h2>
                    <p class="leading-relaxed text-base">Fingerstache flexitarian street art 8-bit waist co, subway tile
                        poke farm.</p>
                </div>
            </div>
            <div class="xl:w-1/3 md:w-1/2 p-4">
                <div class="border border-gray-200 p-6 rounded-lg">
                    <div
                        class="w-10 h-10 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-500 mb-4">
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                             stroke-width="2" class="w-6 h-6" viewBox="0 0 24 24">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg text-gray-900 font-medium title-font mb-2">Ramona Falls</h2>
                    <p class="leading-relaxed text-base">Fingerstache flexitarian street art 8-bit waist co, subway tile
                        poke farm.</p>
                </div>
            </div>
        </div>
        <button
            class="flex mx-auto mt-16 text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">
            Button
        </button>
    </div>
</section>

<section class="text-gray-600 body-font">
    <div class="container px-5 py-24 mx-auto">
        <h1 class="text-3xl font-medium title-font text-gray-900 mb-12 text-center">Témoignages</h1>
        <div class="flex flex-wrap -m-4">
            <div class="p-4 md:w-1/2 w-full">
                <div class="h-full bg-gray-100 p-8 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="block w-5 h-5 text-gray-400 mb-4"
                         viewBox="0 0 975.036 975.036">
                        <path
                            d="M925.036 57.197h-304c-27.6 0-50 22.4-50 50v304c0 27.601 22.4 50 50 50h145.5c-1.9 79.601-20.4 143.3-55.4 191.2-27.6 37.8-69.399 69.1-125.3 93.8-25.7 11.3-36.8 41.7-24.8 67.101l36 76c11.6 24.399 40.3 35.1 65.1 24.399 66.2-28.6 122.101-64.8 167.7-108.8 55.601-53.7 93.7-114.3 114.3-181.9 20.601-67.6 30.9-159.8 30.9-276.8v-239c0-27.599-22.401-50-50-50zM106.036 913.497c65.4-28.5 121-64.699 166.9-108.6 56.1-53.7 94.4-114.1 115-181.2 20.6-67.1 30.899-159.6 30.899-277.5v-239c0-27.6-22.399-50-50-50h-304c-27.6 0-50 22.4-50 50v304c0 27.601 22.4 50 50 50h145.5c-1.9 79.601-20.4 143.3-55.4 191.2-27.6 37.8-69.4 69.1-125.3 93.8-25.7 11.3-36.8 41.7-24.8 67.101l35.9 75.8c11.601 24.399 40.501 35.2 65.301 24.399z"></path>
                    </svg>
                    <p class="leading-relaxed mb-6">Synth chartreuse iPhone lomo cray raw denim brunch everyday carry
                        neutra before they sold out fixie 90's microdosing. Tacos pinterest fanny pack venmo,
                        post-ironic heirloom try-hard pabst authentic iceland.</p>
                    <a class="inline-flex items-center">
                        <img alt="testimonial" src="https://dummyimage.com/106x106"
                             class="w-12 h-12 rounded-full flex-shrink-0 object-cover object-center">
                        <span class="flex-grow flex flex-col pl-4">
                      <span class="title-font font-medium text-gray-900">Holden Caulfield</span>
                      <span class="text-gray-500 text-sm">UI DEVELOPER</span>
                        </span>
                    </a>
                </div>
            </div>
            <div class="p-4 md:w-1/2 w-full">
                <div class="h-full bg-gray-100 p-8 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="block w-5 h-5 text-gray-400 mb-4"
                         viewBox="0 0 975.036 975.036">
                        <path
                            d="M925.036 57.197h-304c-27.6 0-50 22.4-50 50v304c0 27.601 22.4 50 50 50h145.5c-1.9 79.601-20.4 143.3-55.4 191.2-27.6 37.8-69.399 69.1-125.3 93.8-25.7 11.3-36.8 41.7-24.8 67.101l36 76c11.6 24.399 40.3 35.1 65.1 24.399 66.2-28.6 122.101-64.8 167.7-108.8 55.601-53.7 93.7-114.3 114.3-181.9 20.601-67.6 30.9-159.8 30.9-276.8v-239c0-27.599-22.401-50-50-50zM106.036 913.497c65.4-28.5 121-64.699 166.9-108.6 56.1-53.7 94.4-114.1 115-181.2 20.6-67.1 30.899-159.6 30.899-277.5v-239c0-27.6-22.399-50-50-50h-304c-27.6 0-50 22.4-50 50v304c0 27.601 22.4 50 50 50h145.5c-1.9 79.601-20.4 143.3-55.4 191.2-27.6 37.8-69.4 69.1-125.3 93.8-25.7 11.3-36.8 41.7-24.8 67.101l35.9 75.8c11.601 24.399 40.501 35.2 65.301 24.399z"></path>
                    </svg>
                    <p class="leading-relaxed mb-6">Synth chartreuse iPhone lomo cray raw denim brunch everyday carry
                        neutra before they sold out fixie 90's microdosing. Tacos pinterest fanny pack venmo,
                        post-ironic heirloom try-hard pabst authentic iceland.</p>
                    <a class="inline-flex items-center">
                        <img alt="testimonial" src="https://dummyimage.com/107x107"
                             class="w-12 h-12 rounded-full flex-shrink-0 object-cover object-center">
                        <span class="flex-grow flex flex-col pl-4">
              <span class="title-font font-medium text-gray-900">Alper Kamu</span>
              <span class="text-gray-500 text-sm">DESIGNER</span>
            </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="text-gray-600 body-font relative">
    <div class="container px-5 py-24 mx-auto flex sm:flex-nowrap flex-wrap">
        <div
            class="lg:w-2/3 md:w-1/2 bg-gray-300 rounded-lg overflow-hidden sm:mr-10 p-10 flex items-end justify-start relative">
            <!--
            <iframe class="absolute inset-0 w-full h-full pointer-events-none"
                    src="https://www.openstreetmap.org/export/embed.html?bbox=6.033323407173158%2C46.104476816447495%2C6.035576462745667%2C46.1056874663043&amp;layer=mapnik"
                    style="border: 1px solid black; filter: grayscale(1) contrast(1.2) opacity(0.4);"></iframe>
                    -->
            <img src="/img/maps.png" alt="map" class="absolute inset-0 w-full h-full pointer-events-none" />
            <div class="bg-white w-full relative flex flex-wrap py-6 rounded shadow-md">
                <div class="lg:w-1/2 px-6">
                    <h2 class="title-font font-semibold text-gray-900 tracking-widest text-xs">ADRESSE</h2>
                    <p class="mt-1">
                        20, Allée des fées<br/>
                        L'Eluiset<br/>
                        74580 Viry
                    </p>
                </div>
                <div class="lg:w-1/2 px-6 mt-4 lg:mt-0">
                    <h2 class="title-font font-semibold text-gray-900 tracking-widest text-xs">EMAIL</h2>
                    <a href="mailto:thierry.muraz@orange.fr" class="text-indigo-500 leading-relaxed">thierry.muraz@orange.fr</a>
                    <h2 class="title-font font-semibold text-gray-900 tracking-widest text-xs mt-4">TÉLÉPHONE</h2>
                    <p class="leading-relaxed">
                        <a href="tel:0033450774754">+33 450 77 47 54</a> <br/>
                        <a href="tel:0033630811984">+33 630 81 19 84</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="lg:w-1/3 md:w-1/2 bg-white flex flex-col md:ml-auto w-full md:py-8 mt-8 md:mt-0">
            <h2 class="text-gray-900 text-lg mb-1 font-medium title-font">Contact</h2>
            <p class="leading-relaxed mb-5 text-gray-600">N'hésitez pas à nous contacter pour plus d'informations !</p>
            <form action="{{ route('contact') }}" method="POST" id="contact">
                @csrf
                <div class="feedback hidden border-l-4 p-4" role="alert">
                    <p class="font-bold"></p>
                </div>
                <div class="relative mb-4">
                    <label for="name" class="leading-7 text-sm text-gray-600">Nom</label>
                    <input type="text" id="name" name="name"
                           class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                </div>

                <div class="relative mb-4">
                    <label for="email" class="leading-7 text-sm text-gray-600">E-mail</label>
                    <input type="email" id="email" name="email"
                           class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                </div>

                <div class="relative mb-4">
                    <label for="name" class="leading-7 text-sm text-gray-600">Objet</label>
                    <input type="text" id="subject" name="subject"
                           class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                </div>

                <div class="relative mb-4">
                    <label for="message" class="leading-7 text-sm text-gray-600">Message</label>
                    <textarea id="message" name="message"
                              class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                </div>

                <div class="hidden">
                    <input type="checkbox" id="newsletter" name="newsletter">
                    <label for="newsletter">S'inscrire à la newsletter</label>
                </div>

                <button
                    class="text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">
                    Envoyer
                </button>
            </form>
        </div>
    </div>
</section>

<footer class="text-gray-600 body-font">
    <div class="container px-5 py-8 mx-auto flex items-center sm:flex-row flex-col">
        <p class="text-sm text-gray-500 sm:ml-4 sm:pl-4 sm:border-gray-200 sm:py-2 sm:mt-0 mt-4">
            © {{ date("Y") }} L'Allée des Fées
        </p>
    </div>
</footer>

</body>
</html>
