@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <form method="POST" action="/register" class="mt-8 space-y-6 max-w-4xl mx-auto">
        @csrf

        <div class="bg-white p-8 rounded-xl shadow-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- First name -->
                <x-form-field>
                    <x-form-label for="name">Full Name</x-form-label>
                    <x-form-input type="text" name="name" id="name" value="{{ old('name') }}" required/>
                    <x-form-error name="name" />
                </x-form-field>

            
                <!-- Email -->
                <x-form-field>
                    <x-form-label for="email">Email</x-form-label>
                    <x-form-input type="email" name="email" id="email" value="{{ old('email') }}" required />
                    <x-form-error name="email" />
                </x-form-field>

                <!-- Password -->
                <x-form-field>
                    <x-form-label for="password">Password</x-form-label>
                    <x-form-input type="password" name="password" id="password"  required/>
                    <x-form-error name="password" />
                </x-form-field>

                <!-- Confirm Password -->
                <x-form-field>
                    <x-form-label for="password_confirmation">Confirm Password</x-form-label>
                    <x-form-input type="password" name="password_confirmation" id="password_confirmation" required/>
                    <x-form-error name="password_confirmation" />
                </x-form-field>

            </div>

           

            <!-- Buttons -->
            <div class="flex justify-end gap-4 mt-8">
                <a 
                    href="/" 
                    class="inline-block text-sm font-semibold text-gray-600 hover:text-gray-800 px-5 py-2 rounded-md border border-gray-300 hover:bg-gray-100 transition"
                >
                    Cancel
                </a>
                <x-form-button>Register</x-form-button>
            </div>
        </div>
    </form>
@endsection
