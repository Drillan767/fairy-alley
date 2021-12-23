<!doctype html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inscription | L'allée des Fées</title>
    @vite
    @routes
</head>
<body class="register">
<div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row justify-between">
    <a href="/">
        <img src="/img/logo.png" class="h-10 w-10" alt="logo" />
    </a>

    <h1 class="sm:text-text-xl text-3xl font-medium title-font mb-2 text-gray-900 text-center">
        <a href="/">
            L'Allée des Fées
        </a>
    </h1>
    <a href="{{ route('login') }}"
       class="inline-flex items-center bg-gray-100 border-0 py-1 px-3 focus:outline-none hover:bg-gray-200 rounded text-base mt-4 md:mt-0">
        Accès membres
    </a>
</div>

<div class="bg-img">
    <div class="container max-w-5xl mx-auto">
        <div class="bg-white w-full p-8 md:p-12 text-gray-800 leading-normal">
            <h2 class="text-center font-bold break-normal text-3xl md:text-5xl mb-8">
                Formulaire <br /> de premier contact
            </h2>

            <p class="text-center text-sm mb-8">
                Le but de cette fiche est de mieux vous connaître, d’adapter mon cours en conséquence
                ou de vous donner des conseils particuliers, de vous prévenir si le cours n’a pas lieu.
                Le contenu de cette fiche restera strictement confidentiel.
            </p>

            <form action="{{ route('fc.store') }}" method="POST">
                @if(session()->has('success'))
                    <div class="alert alert-success mb-5">
                        {{ session()->get('success') }}
                    </div>
                @endif
                @csrf
                <div class="grid grid-cols-6 gap-4">
                    <div class="col-span-6 sm:col-span-3">
                        <label for="lastname" class="block text-sm font-medium text-gray-700">Nom</label>
                        <input type="text" name="lastname" id="lastname">
                        @error('lastname')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="firstname" class="block text-sm font-medium text-gray-700">Prénom</label>
                        <input type="text" name="firstname" id="firstname">
                        @error('firstname')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                        <input type="email" name="email" id="email" autocomplete="email">
                        @error('email')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="birthday" class="block text-sm font-medium text-gray-700">Date de naissance</label>
                        <input type="date" name="birthday" id="birthday">
                        @error('birthday')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone portable</label>
                        <input type="text" name="phone" id="phone">
                        @error('phone')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="pro" class="block text-sm font-medium text-gray-700">Téléphone fixe / professionnel</label>
                        <input type="text" name="pro" id="pro">
                        @error('pro')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-6">
                        <label for="work" class="block text-sm font-medium text-gray-700">Profession</label>
                        <input type="text" name="work" id="work">
                        @error('work')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <label for="family_situation" class="block text-sm font-medium text-gray-700">Situation familiale</label>
                        <select id="family_situation" name="family_situation">
                            <option value="Célibataire / Divorcé(e)">Célibataire</option>
                            <option value="Marié(e) / paxé(e)">Marié(e) / paxé(e)</option>
                            <option value="Veuf / Veuve">Veuf / Veuve</option>
                        </select>
                        @error('family_situation')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-6 sm:col-span-2">
                        <label for="nb_children" class="block text-sm font-medium text-gray-700">Nombre d'enfants à charge</label>
                        <input type="number" name="nb_children" id="nb_children" value="0">
                        @error('nb_children')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-6 mt-4">
                        <label for="address1" class="block text-sm font-medium text-gray-700">Adresse</label>
                        <input type="text" name="address1" id="address1">
                        @error('address1')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-6">
                        <label for="address2" class="block text-sm font-medium text-gray-700">Complément d'adresse</label>
                        <input type="text" name="address2" id="address2">
                        @error('address2')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                        <label for="zipcode" class="block text-sm font-medium text-gray-700">Code postal</label>
                        <input type="text" name="zipcode" id="zipcode">
                        @error('zipcode')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-6 sm:col-span-6 lg:col-span-4">
                        <label for="city" class="block text-sm font-medium text-gray-700">Ville</label>
                        <input type="text" name="city" id="city">
                        @error('city')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <label for="health_issues" class="block text-sm font-medium text-gray-700">
                            Avez-vous eu des problèmes de santé ?
                        </label>
                        <textarea name="health_issues" id="health_issues"></textarea>
                        @error('health_issues')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <label for="current_health_issues" class="block text-sm font-medium text-gray-700">
                            Avez-vous des problèmes de santé actuellement ? (certificat médical d’aptitude pour la gymnastique)
                        </label>
                        <textarea name="current_health_issues" id="current_health_issues"></textarea>
                        @error('current_health_issues')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <label for="medical_treatment" class="block text-sm font-medium text-gray-700">
                            Suivez-vous un traitement médical ? Si oui lequel ?
                        </label>
                        <textarea name="medical_treatment" id="medical_treatment"></textarea>
                        @error('medical_treatment')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <label for="sports" class="block text-sm font-medium text-gray-700">
                            Sports, loisirs, autres activités :
                        </label>
                        <textarea name="sports" id="sports"></textarea>
                        @error('sports')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <label for="objectives" class="block text-sm font-medium text-gray-700">
                            Que recherchez-vous à travers des cours de gymnastique ?
                        </label>
                        <textarea name="objectives" id="objectives"></textarea>
                        @error('objectives')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <label for="other_data" class="block text-sm font-medium text-gray-700">
                            Compléments d’information ?
                        </label>
                        <textarea name="other_data" id="other_data"></textarea>
                        @error('other_data')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
