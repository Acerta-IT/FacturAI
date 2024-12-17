<x-app-layout>
    <x-slot name="header">
        Configuración
    </x-slot>

    <div class="flex flex-col items-center">
        <div class="w-full max-w-3xl p-8">
            <form method="POST" action="{{ route('settings.update') }}" id="save-settings-form">
                @csrf

                {{-- @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif --}}

                <div class="mb-4">
                    <x-input-label for="template_path" :value="__('Ruta de la plantilla de los Regsitros Primarios')" />
                    <x-text-input id="template_path" class="block mt-1 w-full"
                        type="text"
                        name="template_path"
                        :value="old('template_path', $config['template_path'])" />
                    <x-input-error :messages="$errors->get('template_path')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="excel_input_name" :value="__('Nombre del Excel de entrada (AnexoII)')" />
                    <x-text-input id="excel_input_name" class="block mt-1 w-full"
                        type="text"
                        name="excel_input_name"
                        :value="old('excel_input_name', $config['excel_input_name'])" />
                    <x-input-error :messages="$errors->get('excel_input_name')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="excel_output_name" :value="__('Nombre del Excel resultado')" />
                    <x-text-input id="excel_output_name" class="block mt-1 w-full"
                        type="text"
                        name="excel_output_name"
                        :value="old('excel_output_name', $config['excel_output_name'])" />
                    <x-input-error :messages="$errors->get('excel_output_name')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="content_for_system" :value="__('Rol para la IA')" />
                    <textarea id="content_for_system"
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        name="content_for_system">{{ old('content_for_system', $config['content_for_system']) }}</textarea>
                    <x-input-error :messages="$errors->get('content_for_system')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="get_invoice_data_prompt" :value="__('Prompt para obtener datos de factura en pdf')" />
                    <textarea id="get_invoice_data_prompt" class="block mt-1 w-full h-48 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        name="get_invoice_data_prompt">{{ old('get_invoice_data_prompt', $config['get_invoice_data_prompt']) }}</textarea>
                    <x-input-error :messages="$errors->get('get_invoice_data_prompt')" class="mt-2" />
                </div>

                <div class="mb-8">
                    <x-input-label for="get_excel_invoices_data_prompt" :value="__('Prompt para obtener la lista de factuas del AnexoII')" />
                    <textarea id="get_excel_invoices_data_prompt" class="block mt-1 w-full h-48 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        name="get_excel_invoices_data_prompt">{{ old('get_excel_invoices_data_prompt', $config['get_excel_invoices_data_prompt']) }}</textarea>
                    <x-input-error :messages="$errors->get('get_excel_invoices_data_prompt')" class="mt-2" />
                </div>

                <div class="mb-8">
                    <x-input-label :value="__('Mapeos de tablas del AnexoII a las hojas del Excel resultado')" class="mb-4" />
                    <div class="space-y-4">
                        @foreach($config['excel_invoices_table_mappings'] as $key => $value)
                        <div class="flex gap-4">
                            <div class="w-1/2">
                                <x-text-input
                                    name="excel_invoices_table_mappings_keys[]"
                                    type="text"
                                    class="w-full"
                                    :value="old('excel_invoices_table_mappings_keys.'.$loop->index, $key)" />
                                <x-input-error :messages="$errors->get('excel_invoices_table_mappings_keys.'.$loop->index)" class="mt-2" />
                            </div>
                            <div class="w-1/2">
                                <x-text-input
                                    name="excel_invoices_table_mappings_values[]"
                                    type="text"
                                    class="w-full"
                                    :value="old('excel_invoices_table_mappings_values.'.$loop->index, $value)" />
                                <x-input-error :messages="$errors->get('excel_invoices_table_mappings_values.'.$loop->index)" class="mt-2" />
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-8">
                    <x-input-label :value="__('Nombres de las hojas de los Excels')" class="mb-4" />
                    <div class="space-y-4">
                        @foreach($config['sheet_names'] as $key => $value)
                        <div class="flex gap-4">
                            <div class="w-1/2">
                                <x-text-input
                                    name="sheet_names_keys[]"
                                    type="text"
                                    class="w-full bg-secondary"
                                    :value="$key"
                                    readonly/>
                            </div>
                            <div class="w-1/2">
                                <x-text-input
                                    name="sheet_names_values[]"
                                    type="text"
                                    class="w-full"
                                    :value="old('sheet_names_values.'.$loop->index, $value)" />
                                <x-input-error :messages="$errors->get('sheet_names_values.'.$loop->index)" class="mt-2" />
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <x-input-label for="header_row_key" :value="__('Header row key')" />
                    <x-text-input id="header_row_key" class="block mt-1 w-full"
                        type="text"
                        name="header_row_key"
                        :value="old('header_row_key', $config['header_row_key'])" />
                    <x-input-error :messages="$errors->get('header_row_key')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="totals_row_key" :value="__('Totals row key')" />
                    <x-text-input id="totals_row_key" class="block mt-1 w-full"
                        type="text"
                        name="totals_row_key"
                        :value="old('totals_row_key', $config['totals_row_key'])" />
                    <x-input-error :messages="$errors->get('totals_row_key')" class="mt-2" />
                </div>

                <div class="mb-4 mt-10">
                    <label for="debug" class="inline-flex items-center">
                        <input id="debug" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            name="debug"
                            {{ old('debug', $config['debug']) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-600">{{ __('Modo debug') }}</span>
                    </label>
                    <x-input-error :messages="$errors->get('debug')" class="mt-2" />
                </div>
            </form>

            <form method="POST" action="{{ route('settings.reset') }}" id="reset-form">
                @csrf
            </form>
        </div>

        <!-- Buttons Container -->
        <div class="flex items-center justify-end mt-8 space-x-4">
            <x-danger-button onclick="document.getElementById('reset-form').submit()">
                {{ __('Restaurar cambios') }}
            </x-danger-button>

            <x-secondary-button type="button" onclick="submitSettingsForm('{{ route('settings.saveDefault') }}')">
                {{ __('Predeterminado') }}
            </x-secondary-button>

            <x-primary-button onclick="document.getElementById('save-settings-form').submit()">
                {{ __('Guardar cambios') }}
            </x-primary-button>
        </div>
    </div>
</x-app-layout>

<script>
    function submitSettingsForm(action) {
        const form = document.getElementById('save-settings-form');
        const originalAction = form.action;
        form.action = action;
        form.submit();
        form.action = originalAction; // Restore original action
    }
</script>
