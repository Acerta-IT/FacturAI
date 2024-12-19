<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    public function index()
    {
        $config = json_decode(File::get(config("facturai.config_path")), true);
        return view('settings.index', compact('config'));
    }

    /**
     * Update the configuration.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'template_path' => 'required|string',
                'excel_input_name' => 'required|string',
                'excel_output_name' => 'required|string',
                'content_for_system' => 'required|string',
                'get_invoice_data_prompt' => 'required|string',
                'get_excel_invoices_data_prompt' => 'required|string',
                'excel_invoices_table_mappings_keys' => 'required|array',
                'excel_invoices_table_mappings_keys.*' => 'required|string|min:1',
                'excel_invoices_table_mappings_values' => 'required|array',
                'excel_invoices_table_mappings_values.*' => 'required|string|min:1',
                'sheet_names_keys' => 'required|array',
                'sheet_names_keys.*' => 'required|string|min:1',
                'sheet_names_values' => 'required|array',
                'sheet_names_values.*' => 'required|string|min:1',
                'header_row_key' => 'required|string',
                'totals_row_key' => 'required|string'
            ], [
                'template_path.required' => 'Por favor, escribe la ruta de la plantilla.',
                'template_path.string' => 'Por favor, escribe una ruta válida para la plantilla.',
                'excel_input_name.required' => 'Por favor, escribe un nombre para el excel de entrada.',
                'excel_input_name.string' => 'El nombre del excel de entrada no es válido.',
                'excel_output_name.required' => 'Por favor, escribe un nombre para el excel de salida.',
                'excel_output_name.string' => 'El nombre del excel de salida no es válido.',
                'content_for_system.required' => 'Por favor, escribe el rol para la IA.',
                'content_for_system.string' => 'El rol para la IA no es válido.',
                'get_invoice_data_prompt.required' => 'Por favor, escribe un prompt para obtener los datos de las facturas.',
                'get_invoice_data_prompt.string' => 'El prompt para obtener los datos de las facturas no es válido.',
                'get_excel_invoices_data_prompt.required' => 'Por favor, escribe un prompt para obtener las facturas del excel de entrada.',
                'get_excel_invoices_data_prompt.string' => 'El prompt para obtener las facturas del excel de entrada no es válido.',
                'excel_invoices_table_mappings_keys.*.required' => 'Por favor, escribe una clave para la tabla.',
                'excel_invoices_table_mappings_keys.*.string' => 'La clave no es válida.',
                'excel_invoices_table_mappings_values.*.required' => 'Por favor, escribe un valor para la tabla.',
                'excel_invoices_table_mappings_values.*.string' => 'El valor no es válido.',
                'sheet_names_keys.*.required' => 'Por favor, escribe una clave para la hoja.',
                'sheet_names_keys.*.string' => 'La clave no es válida.',
                'sheet_names_values.*.required' => 'Por favor, escribe un valor para la hoja.',
                'sheet_names_values.*.string' => 'El valor no es válido.',
                'header_row_key.required' => 'Por favor, escribe una clave para la fila de encabezado.',
                'header_row_key.string' => 'La clave no es válida.',
                'totals_row_key.required' => 'Por favor, escribe una clave para la fila de totales.',
                'totals_row_key.string' => 'La clave no es válida.'
            ]);

            // Read current config to maintain structure
            $config = json_decode(File::get(config('facturai.config_path')), true);

            // Update simple fields
            $config['template_path'] = $validated['template_path'];
            $config['excel_input_name'] = $validated['excel_input_name'];
            $config['excel_output_name'] = $validated['excel_output_name'];
            $config['content_for_system'] = $validated['content_for_system'];
            $config['get_invoice_data_prompt'] = $validated['get_invoice_data_prompt'];
            $config['get_excel_invoices_data_prompt'] = $validated['get_excel_invoices_data_prompt'];
            $config['header_row_key'] = $validated['header_row_key'];
            $config['totals_row_key'] = $validated['totals_row_key'];

            // Set debug to true if checked, false if not present
            $config['debug'] = $request->has('debug');

            // Update excel_invoices_table_mappings
            $mappings = [];
            foreach ($validated['excel_invoices_table_mappings_keys'] as $index => $key) {
                if (isset($validated['excel_invoices_table_mappings_values'][$index])) {
                    $mappings[$key] = $validated['excel_invoices_table_mappings_values'][$index];
                }
            }
            $config['excel_invoices_table_mappings'] = $mappings;

            // Update sheet_names
            $sheets = [];
            foreach ($validated['sheet_names_keys'] as $index => $key) {
                if (isset($validated['sheet_names_values'][$index])) {
                    $sheets[$key] = $validated['sheet_names_values'][$index];
                }
            }
            $config['sheet_names'] = $sheets;

            // Save the updated config
            File::put(config('facturai.config_path'), json_encode($config, JSON_PRETTY_PRINT));

            return redirect()->route('settings.index')->with('status', [
                'message' => 'Configuración actualizada correctamente.',
                'class' => 'toast-success'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput($request->all());  // Explicitly pass all input data
        }
    }

    /**
     * Reset the configuration to the default values.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset()
    {
        $defaultConfig = File::get(config('facturai.default_config_path'));
        File::put(config('facturai.config_path'), $defaultConfig);

        return redirect()->route('settings.index')->with('status', [
            'message' => 'Configuración restablecida a los valores predeterminados.',
            'class' => 'toast-success'
        ]);
    }

    /**
     * Save the current configuration as the default values.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveDefault(Request $request)
    {
        // First update the config.json using the existing update method
        $response = $this->update($request);

        // If the update was successful, copy to config_default.json
        if ($response->getSession()->has('status') && $response->getSession()->get('status')['class'] === 'toast-success') {
            $currentConfig = File::get(config('facturai.config_path'));
            File::put(config('facturai.default_config_path'), $currentConfig);

            return redirect()->route('settings.index')->with('status', [
                'message' => 'Configuración actual guardada como predeterminada.',
                'class' => 'toast-success'
            ]);
        }

        // If update failed, return the response with validation errors
        return $response;
    }
}
