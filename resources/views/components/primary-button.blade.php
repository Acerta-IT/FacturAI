<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'px-4 py-3 bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150
                   hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
                   disabled:bg-gray-500 disabled:cursor-not-allowed disabled:hover:bg-gray-500'
    ]) }}>
    {{ $slot }}
</button>
