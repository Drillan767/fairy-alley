@extends('layouts.front')

@section('title', "{$page->title} | L'allée des Fées")

@section('content')
    <div style="background-image: url('{{ $page->illustration }}')" class="bg-no-repeat bg-cover h-50vh bg-center"></div>
    <div class="container max-w-5xl mx-auto -mt-32">
        <div class="bg-white w-full p-8 md:p-24 text-xl md:text-2xl text-gray-800 leading-normal">
            <h2 class="text-center font-bold break-normal text-3xl md:text-5xl mb-8">
                {{ $page->title }}
            </h2>

            <div class="prose max-w-none text-justify">
                {!! $page->content !!}
            </div>

            @role('administrator')
            <div class="flex justify-end mt-4">
                <a
                    href="{{ route('pages.edit', ['page' => $page->id]) }}"
                    class="inline-flex items-center bg-gray-100 border-0 py-1 px-3 focus:outline-none hover:bg-gray-200 rounded text-base mt-4 md:mt-0"
                >
                    Éditer la page
                </a>
            </div>
            @endrole
        </div>
    </div>
@endsection
