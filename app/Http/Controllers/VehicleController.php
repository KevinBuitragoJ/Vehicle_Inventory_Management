<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    
    #  Muestra la lista de vehiculos
    public function index(Request $request)
    {
        $query = Vehicle::query();

        #Filtro por marca, color o modelo
        $filterBy = $request->input('filter_by');
        $searchTerm = $request->input('search');

        if ($searchTerm && $filterBy){
            
            switch ($filterBy){
                case 'make':
                    $query->where('make', 'LIKE', '%' . $searchTerm . '%');
                    break;
                case 'color':
                    $query->where('color', 'LIKE', '%' . $searchTerm . '%');
                    break;
                case 'model':
                    $query->where('model', 'LIKE', '%' . $searchTerm . '%');
                    break;
                default:
                    break;
            }
        }else{
            $searchTerm = null;
        }

        #cuenta cuantos vehiculo hay registrados
        $vehicleCount = $query->count();

        #Paginación 6 por pagina y coinserva los filtros
        $vehicles = $query->paginate(6)->withQueryString();

        return view('vehicles.index', compact('vehicles', 'searchTerm', 'vehicleCount'));
    }

    # Muestra el formulario para agregar un nuevo vehiculo
    public function create()
    {
        return view('vehicles.create');
    }

    # Guarda nuevo vehiculo en la BD
    public function store(Request $request)
    {
        //Validación de datos
        $request->validate([
            'make' => 'required|string|min:3|max:30',
            'license_plate' => 'required|string|regex:/^[a-zA-Z]{3}[0-9]{3}$/|unique:vehicles,license_plate',
            'color' => 'required|string|min:3|max:30',
            'model' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'purchase_date' => 'required|date|before_or_equal:today|after_or_equal:1900-01-01',
            'accident_report' => 'boolean',
        ],[ 
            #mensajes de error personalizados
            'make.required' => 'La marca es obligatoria.',
            'make.min' => 'Debe tener minimo de 3 caracteres.',
            'make.max' => 'Debe tener maximo 30 caracteres.',
            'license_plate.required' => 'La placa es obligatoria',
            'license_plate.regex' => 'El formato de la placa no es valida',
            'license_plate.unique' => 'Esta placa ya se encuentra registrada',
            'color.required' => 'El color es obligatorio',
            'color.min' => 'Debe tener minimo de 3 caracteres.',
            'color.max' => 'Debe tener maximo 30 caracteres.',
            'model.max' => 'La fecha no es valida',
            'model.min' => 'La fecha no debe ser anterior al año 1.900',
            'purchase_date.before_or_equal' => 'La fecha no debe ser futura',
            'purchase_date.after_or_equal' => 'La fecha no debe ser anterior al año 1.900',
        ]);

        // Convertir a mayúsculas
        $request['license_plate'] = strtoupper($request['license_plate']); 
        $request['make'] = strtoupper($request['make']); 
        $request['color'] = strtoupper($request['color']); 

        Vehicle::create($request->all());

        return redirect()->route('vehicles.index')
        ->with('success', 'Vehículo registrado exitosamente.');
    }

    #Muestra los detalles del vehiculo seleccionado
    public function show(Vehicle $vehicle)
    {
        return view('vehicles.show', compact('vehicle'));
    }

    # Muestra el formulario para editar un vehiculo existente
    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        //Validación de datos
        $request->validate([
            'make' => 'required|string|min:3|max:30',
            'license_plate' => ['required','string','regex:/^[a-zA-Z]{3}[0-9]{3}$/',Rule::unique('vehicles', 'license_plate')->ignore($vehicle->id)],
            'color' => 'required|string|min:3|max:30',
            'model' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'purchase_date' => 'required|date|before_or_equal:today|after_or_equal:1900-01-01',
            'accident_report' => 'boolean',
        ],[
            #mensajes de error personalizados
            'make.required' => 'La marca es obligatoria.',
            'make.min' => 'Debe tener minimo de 3 caracteres.',
            'make.max' => 'Debe tener maximo 30 caracteres.',
            'license_plate.required' => 'La placa es obligatoria',
            'license_plate.regex' => 'El formato de la placa no es valida',
            'license_plate.unique' => 'Esta placa ya se encuentra registrada',
            'color.required' => 'El color es obligatorio',
            'color.min' => 'Debe tener minimo de 3 caracteres.',
            'color.max' => 'Debe tener maximo 30 caracteres.',
            'model.max' => 'La fecha no es valida',
            'model.min' => 'La fecha no debe ser anterior al año 1.900',
            'purchase_date.before_or_equal' => 'La fecha no debe ser futura',
            'purchase_date.after_or_equal' => 'La fecha no debe ser anterior al año 1.900',
        ]);

        // Convertir a mayúsculas
        $request['license_plate'] = strtoupper($request['license_plate']); 
        $request['make'] = strtoupper($request['make']); 
        $request['color'] = strtoupper($request['color']); 
        
        $request['accident_report'] = $request->has('accident_report');
        $vehicle->update($request->all());

        return redirect()->route('vehicles.index')
        ->with('success', 'Vehículo actualizado exitosamente.');
    }

    # Elimina un vehiculo de la BD
    public function destroy(Vehicle $vehicle)
    {
       $vehicle->delete();
       return redirect()->route('vehicles.index')
       ->with('success', 'Vehículo eliminado exitosamente.');
    }
}
