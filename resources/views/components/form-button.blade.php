

<button {{$attributes->merge(['class' => 'bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 transition', 'type' => 'submit'])}}>
    {{$slot}}
</button>