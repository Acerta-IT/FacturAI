<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-3 bg-gray-500 border border-gray-500 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
