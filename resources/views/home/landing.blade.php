<!doctype html>
<html data-theme="light" lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <title>L'allée des fées</title>
    @routes
    @vite
</head>
<body class="text-gray-600 body-font">
<header>
    <div class="container mx-auto mt-6 flex flex-wrap flex-col md:flex-row items-center justify-between absolute top-0 left-0 right-0">
        <div class="flex items-center flex-shrink-0 text-white mr-6">
            <img src="/img/ADF-Logo.png" alt="logo" />
        </div>
        <div>
            <a href="{{ route('login') }}"
               class="inline-flex items-center bg-gray-100 border-0 py-2 px-4 focus:outline-none hover:bg-gray-200 rounded text-base mt-4 md:mt-0">
                Accès membres
            </a>
        </div>

    </div>
    <div class="landing flex flex-col justify-center">
        <div class="welcome-message">
            <h2>
                Sèverine & Thierry vous proposent
            </h2>
            <h2>
                un accompagnement bien-être <br /> dans la bienveillance et l'harmonie
            </h2>
        </div>
    </div>
</header>

<div class="flex justify-center my-4">
    <button
        class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg preview-modal" data-video="visite">
        Venez découvrir le site
    </button>
</div>

<div class="px-4">
    <p class="text-2xl text-center text-gray-900">
        Venez profiter d'un environnement unique pour découvrir l'univers de la relaxation et du bien-être.
    </p>
</div>

<section>
    <div class="services">
        @foreach($services as $service)
            <div class="rounded-lg">
                <a href="{{ route('pages.show', ['slug' => $service->page->slug]) }}">
                    <img class="h-40 rounded-t w-full object-cover object-center" src="{{ $service->thumbnail->url }}" alt="content">
                </a>

                <div class="p-3 bg-[#ECFCCB]">
                    <h2 class="text-lg text-gray-900 font-medium title-font text-center mb-4">{{ $service->title }}</h2>
                    <p>
                        <a href="{{ route('pages.show', ['slug' => $service->page->slug]) }}" class="text-sm mt-3 text-indigo-500 inline-flex items-center">En savoir plus
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                                <path d="M5 12h14M12 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</section>
<section>
    <div class="container px-5 py-10 mx-auto">
        <div class="md:-mx-4 xl:py-4">
            <div class="px-4 sm:text-center mt-12 md:mt-0">
<!--                <button
                    class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg preview-modal" data-video="echauffement">
                    Aperçu d'une séance de gymnastique rééducative
                </button>-->

                <br />
                <a href="{{ route('fc.create') }}"
                   class="flex mx-auto mt-16 mr-4 text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg block sm:inline-block">
                    Laissez vos coordonnées pour découvrir les cours de gymnastique
                </a>

            </div>
        </div>
    </div>
</section>

<!--section>
    <div class="container px-5 py-12 mx-auto">
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
</section>-->

<section class="relative">
    <div class="container px-5 py-12 mx-auto flex sm:flex-nowrap flex-wrap">
        <div class="lg:w-2/3 md:w-1/2 flex justify-end bg-gray-300 rounded-lg overflow-hidden sm:mr-10 p-10 flex items-end justify-start relative">
            <img src="/img/map.png" alt="map" class="absolute inset-0 w-full h-full object-cover" />
            <div class="bg-white bg-opacity-80 relative flex flex-wrap py-6 rounded shadow-md">
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
                    <a href="mailto:adf@alleedesfees.fr" class="text-indigo-500 leading-relaxed">adf@alleedesfees.fr</a>
                    <h2 class="title-font font-semibold text-gray-900 tracking-widest text-xs mt-4">TÉLÉPHONE</h2>
                    <p class="leading-relaxed">
                        <a href="tel:0033630811984">+33 630 81 19 84</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="lg:w-1/3 md:w-1/2 bg-white flex flex-col md:ml-auto w-full md:py-8 mt-8 md:mt-0">
            <h2 class="text-gray-900 text-lg mb-1 font-medium title-font">Contact</h2>
            <p class="leading-relaxed mb-5 text-gray-600">Contactez-nous pour plus d'informations !</p>
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

<footer>
    <div class="container px-5 py-8 mx-auto text-center">
        <p class="text-sm text-gray-500 sm:ml-4 sm:mt-0 mt-4">
            © {{ date("Y") }} L'Allée des Fées
        </p>
    </div>
</footer>

<!-- Modal  -->
<div class="landing-modal hidden">

    <!-- Modal content -->
    <div class="content">
        <div class="header">
            <span class="close">&times;</span>
        </div>
        <div class="body">
            <video class="w-full hidden echauffement" src="{{$echauffement}}" controls></video>
            <video class="w-full hidden visite" src="{{$visite}}" controls muted></video>
        </div>
    </div>

</div>

<script>
    const modalBox = document.querySelector('.landing-modal');
    const modalClose = document.querySelector('span.close');
    const body = document.querySelector('body');
    const visite = modalBox.querySelector('.visite');
    const echauffement = modalBox.querySelector('.echauffement');

    document.querySelectorAll('button.preview-modal').forEach((btn) => {
        btn.addEventListener('click', () => {
            modalBox.classList.remove('hidden');
            modalBox.querySelector('.' + btn.getAttribute('data-video')).classList.remove('hidden');
            body.style.overflow = 'hidden';
            visite.play();
        })
    })

    modalClose.addEventListener('click', () => {
        closeModal();
    })

    window.onclick = (e) => {
        if (e.target === modalBox) {
            closeModal()
        }
    }

    function closeModal() {
        visite.pause();
        echauffement.pause();
        visite.classList.add('hidden');
        echauffement.classList.add('hidden');
        modalBox.classList.add('hidden');
        body.style.overflow = 'initial';
    }
</script>

</body>
</html>
