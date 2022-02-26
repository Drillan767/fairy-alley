@extends('layouts.front')

@section('title', "Inscription | L'allée des Fées")

@section('content')
    <div class="register">
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
                                <input type="text" name="lastname" value="{{ old('lastname') }}" id="lastname">
                                @error('lastname')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="firstname" class="block text-sm font-medium text-gray-700">Prénom</label>
                                <input type="text" name="firstname" value="{{ old('firstname') }}" id="firstname">
                                @error('firstname')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                                <input type="email" name="email" value="{{ old('email') }}" id="email" autocomplete="email">
                                @error('email')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <div class="flex gap-x-3">
                                    <div class="flex-1">
                                        <label for="birthday" class="block text-sm font-medium text-gray-700">Date de naissance</label>
                                        <input type="date" name="birthday" value="{{ old('birthday') }}" id="birthday">
                                        @error('birthday')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="flex-1">
                                        <label for="birthday" class="block text-sm font-medium text-gray-700">Genre</label>
                                        <div class="flex mt-2 justify-around">
                                            <div class="form-check">

                                                <label class="form-check-label inline-block text-gray-800">
                                                    <input type="radio" name="gender" value="H" id="flexRadioDefault1">
                                                    Homme
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label inline-block text-gray-800">
                                                    <input type="radio" name="gender" value="F" id="flexRadioDefault1">
                                                    Femme
                                                </label>
                                            </div>
                                        </div>
                                        </div>

                                        @error('birthday')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone portable</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" id="phone">
                                @error('phone')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="pro" class="block text-sm font-medium text-gray-700">Téléphone fixe / professionnel</label>
                                <input type="text" name="pro" value="{{ old('pro') }}" id="pro">
                                @error('pro')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6">
                                <label for="work" class="block text-sm font-medium text-gray-700">Profession</label>
                                <input type="text" name="work" value="{{ old('work') }}" id="work">
                                @error('work')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <label for="family_situation" class="block text-sm font-medium text-gray-700">Situation familiale</label>
                                <select id="family_situation" name="family_situation" value="{{ old('family_situation') }}">
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
                                <input type="number" name="nb_children" value="{{ old('nb_children') ?? '0' }}" id="nb_children">
                                @error('nb_children')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 mt-4">
                                <label for="address1" class="block text-sm font-medium text-gray-700">
                                    Cours désiré
                                    <select name="lesson" required>
                                        <option value="">Sélectionner un cours...</option>
                                        @foreach($lessons as $lesson)
                                            <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>

                            <div class="col-span-6 mt-4">
                                <label for="address1" class="block text-sm font-medium text-gray-700">Adresse</label>
                                <input type="text" name="address1" value="{{ old('address1') }}" id="address1">
                                @error('address1')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6">
                                <label for="address2" class="block text-sm font-medium text-gray-700">Complément d'adresse</label>
                                <input type="text" name="address2" value="{{ old('address2') }}" id="address2">
                                @error('address2')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                                <label for="zipcode" class="block text-sm font-medium text-gray-700">Code postal</label>
                                <input type="text" name="zipcode" value="{{ old('zipcode') }}" id="zipcode">
                                @error('zipcode')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-6 lg:col-span-4">
                                <label for="city" class="block text-sm font-medium text-gray-700">Ville</label>
                                <input type="text" name="city" value="{{ old('city') }}" id="city">
                                @error('city')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-6">
                                <label for="health_issues" class="block text-sm font-medium text-gray-700">
                                    Avez-vous eu des problèmes de santé ?
                                </label>
                                <textarea name="health_issues" value="{{ old('health_issues') }}" id="health_issues"></textarea>
                                @error('health_issues')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-6">
                                <label for="current_health_issues" class="block text-sm font-medium text-gray-700">
                                    Avez-vous des problèmes de santé actuellement ? (certificat médical d’aptitude pour la gymnastique)
                                </label>
                                <textarea name="current_health_issues" value="{{ old('current_health_issues') }}" id="current_health_issues"></textarea>
                                @error('current_health_issues')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-6">
                                <label for="medical_treatment" class="block text-sm font-medium text-gray-700">
                                    Suivez-vous un traitement médical ? Si oui lequel ?
                                </label>
                                <textarea name="medical_treatment" value="{{ old('medical_treatment') }}" id="medical_treatment"></textarea>
                                @error('medical_treatment')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-6">
                                <label for="sports" class="block text-sm font-medium text-gray-700">
                                    Sports, loisirs, autres activités :
                                </label>
                                <textarea name="sports" value="{{ old('sports') }}" id="sports"></textarea>
                                @error('sports')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-6">
                                <label for="objectives" class="block text-sm font-medium text-gray-700">
                                    Que recherchez-vous à travers des cours de gymnastique ?
                                </label>
                                <textarea name="objectives" value="{{ old('objectives') }}" id="objectives"></textarea>
                                @error('objectives')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-6">
                                <label for="other_data" class="block text-sm font-medium text-gray-700">
                                    Compléments d’information ?
                                </label>
                                <textarea name="other_data" value="{{ old('other_data') }}" id="other_data"></textarea>
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
    </div>
@endsection

</body>
