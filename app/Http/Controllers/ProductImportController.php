<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;

// Errores de validación HTTP (Request::validate)
use Illuminate\Validation\ValidationException as HttpValidation;
// Errores de validación a nivel de Excel (si usas reglas en el import)
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidation;

use Throwable;

class ProductImportController extends Controller
{
    public function import(Request $request)
    {
        try {
            // 1) Validación del request
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv|max:20480', // 20 MB
            ]);

            // 2) Ejecutar import
            Excel::import(new ProductsImport, $request->file('file'));

            return response()->json([
                'ok'      => true,
                'message' => 'Importación completada',
            ], Response::HTTP_OK);

        } catch (HttpValidation $e) {
            // Errores de validación del request (archivo faltante, mime, tamaño, etc.)
            return response()->json([
                'ok'      => false,
                'message' => 'Validación del request',
                'errors'  => $e->errors(), // estructura { campo: [mensajes...] }
            ], Response::HTTP_UNPROCESSABLE_ENTITY);

        } catch (ExcelValidation $e) {
            // Errores por fila/columna durante el import (si defines reglas en tu Import)
            $failures = collect($e->failures())->map(function ($f) {
                return [
                    'row'       => $f->row(),
                    'attribute' => $f->attribute(),
                    'errors'    => $f->errors(),
                    'values'    => $f->values(),
                ];
            })->values();

            return response()->json([
                'ok'       => false,
                'message'  => 'Errores de validación en el Excel',
                'failures' => $failures,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);

        } catch (Throwable $e) {
            // 500: log detallado, respuesta limpia para el cliente
            Log::error('Products import failed', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'ok'      => false,
                'message' => config('app.debug')
                    ? $e->getMessage() // en local puedes ver el detalle
                    : 'Ocurrió un error al procesar el archivo', // en prod, mensaje genérico
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
