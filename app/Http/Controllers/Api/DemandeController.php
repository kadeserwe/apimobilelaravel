<?php

namespace App\Http\Controllers\Api;
use App\Models\Demande;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DemandeController extends Controller
{
    //

/*
    public function index(Request $request)
    { try { 
       // $paginate = $request->query('pagination', 'true'); // les données proviennent  uniquement issues de l’URL
       $paginate = filter_var($request->query('pagination', true), FILTER_VALIDATE_BOOLEAN);//Convertit la valeur en booléen (true ou false) grâce à FILTER_VALIDATE_BOOLEAN.
       $perPage = $request->query('per_page', '10'); 

       $query = Demande::with('procedures');
         $query = Demande::with('procedures');


        // 🎯 Filtre par procedure_ids
        if ($request->filled('procedure_ids')) {
            $procedureIds = is_array($request->procedure_ids) 
                ? $request->procedure_ids 
                : explode(',', $request->procedure_ids);

            $query->whereHas('procedures', function ($q) use ($procedureIds) {
                $q->whereIn('procedures.id', $procedureIds);
            });
        }

         if($paginate === 'false'){

          //  $demande = Demande::all();
             $demande = Demande::with('procedures')->get();
           // $demande = Demande::with('relation')->get();

        return response()->json([
            'message' => 'demande retrieved successfully',
            'data' => $demande,
            
            'total_page' => $demande->count(),
        ], 200);

         }else{

            $demande = Demande::paginate($perPage);

        return response()->json([
            'message' => 'demande retrieved successfully',
            'data' => $demande,
            'dataItems' => $demande->items(),
            'current_page' => $demande->currentPage(),
            'per_page' => $demande->perPage(),
            'total' => $demande->total(),
            'total_page' => $demande->lastPage(),
        ], 200);
         }

         } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => true,
            'message' => "Erreur .",
            'exception' => $e->getMessage(), // utile en dev
        ], 500);
    }
        
    }
*/
    public function index(Request $request)
{
    try {
        $paginate = filter_var($request->query('pagination', true), FILTER_VALIDATE_BOOLEAN);
        $perPage  = (int) $request->query('per_page', 10);

        $query = Demande::with('procedures');

        // 🎯 Filtre par procedure_ids
      /*  if ($request->filled('procedure_ids')) {
            $procedureIds = is_array($request->procedure_ids) 
                ? $request->procedure_ids 
                : explode(',', $request->procedure_ids);

            $query->whereHas('procedures', function ($q) use ($procedureIds) {
                $q->whereIn('procedures.id', $procedureIds);
            });
        }
*/
          // 🎯 Filtre par procedure_id (via pivot)
      /*  if ($request->filled('procedure_id')) {
            $procedureId = $request->procedure_id;

            $query->whereHas('procedures', function ($q) use ($procedureId) {
                $q->where('demande_procedure.procedure_id', $procedureId);
            });
        }*/

          // 🎯 Filtre par plusieurs procedures (pivot)
      if ($request->filled('procedure_ids')) {
       $procedureIds = is_array($request->procedure_ids) 
        ? $request->procedure_ids 
        : explode(',', $request->procedure_ids);

        $query->whereHas('procedures', function ($q) use ($procedureIds) {
        $q->whereIn('demande_procedure.procedure_id', $procedureIds);
                   });
           }
        if (!$paginate) {
            $demandes = $query->get();

            return response()->json([
                'status' => true,
                'message' => 'Demandes récupérées avec succès',
                'data' => $demandes,
                'total' => $demandes->count(),
            ]);
        }

        $demandes = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'message' => 'Demandes récupérées avec succès',
            'data' => $demandes->items(),
            //'meta' => [
                'current_page' => $demandes->currentPage(),
                'size_page' => $demandes->perPage(),
                'size_page_in' => $demandes->total(),
                'total' => $demandes->total(),
                'total_pages' => $demandes->lastPage(),
           // ],
        ]);
    } catch (\Exception $e) {
        return $this->errorResponse("La récupération des demandes a échoué", $e);
    }
}


    public function show($id)
    {

        try {  
        $demande = Demande::with('procedures')->findOrFail($id);
       //  $demande = Demande::with('procedures')->get();
      
        return response()->json([
            'status' => true,
            'message' => 'demande retrieved successfully',
            'data' => $demande
        ], 200);
         } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => true,
            'message' => "Erreur Id  n'existe pas",
            'exception' => $e->getMessage(), // utile en dev
        ], 500);
    }
    }

/*
        public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|min:3',
            'description' => 'nullable|string',
            'procedure_ids' => 'array',
            'procedure_ids.*' => 'exists:procedures,id',
        ]);

        $demande = Demande::create($validated);

        if (isset($validated['procedure_ids'])) {
            $demande->procedures()->attach($validated['procedure_ids']);
        }

        return response()->json([
            'message' => 'Demande créée avec succès',
            'data' => $demande->load('procedures')
        ], 201);
    }*/
    
    public function store(Request $request)
    {
        
      
         $validatedData = $request->validate([
        'libelle' => 'required|string|max:255',
        'description' => 'nullable|string|min:3',
        'procedure_ids' => 'array',
        'procedure_ids.*' => 'exists:procedures,id',
       // 'roles.*' => 'integer|exists:roles,id',
       // 'actions' => 'nullable|array', // Changé de required à nullable
      //  'actions.*' => 'integer|exists:actions,id',
        ]);
        try {  
        $demande = Demande::create($validatedData);
        
        // Creation de la relation many-to-many
        if (isset($validatedData['procedure_ids'])) {
            // attach() ajoute des enregistrements dans la table pivot.
            $demande->procedures()->attach($validatedData['procedure_ids']);
        }
       
        return response()->json([
            'status' => true,
            'message' => 'demande created successfully',
            'data' => $demande->load('procedures'),
        ], 201);

        } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => true,
            'message' => "L'ajout de la procédure a échoué.",
            'exception' => $e->getMessage(), // utile en dev
        ], 500);
    }
    }
    public function update(Request $request, Demande $demande)
{
    $validated = $request->validate([
        'libelle' => 'required|string|max:255',
        'description' => 'nullable|string|min:3',
        'procedure_ids' => 'array',
        'procedure_ids.*' => 'exists:procedures,id',
    ]);

    try {
        // Mise à jour des colonnes "simples"
        $demande->update($validated);

        // Mise à jour de la relation many-to-many
        if (isset($validated['procedure_ids'])) {
            // Remplace les anciennes procédures par les nouvelles
            $demande->procedures()->sync($validated['procedure_ids']);
        }

        return response()->json([
            'status' => true,
            'message' => 'Demande updated successfully',
            'data' => $demande->load('procedures'),
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => true,
            'message' => "La modification de la demande a échoué.",
            'exception' => $e->getMessage(),
        ], 500);
    }
}


      public function destroy(Demande $demande)
{
    try {
        // Si la demande est liée à des procédures (many-to-many), on les détache d'abord
        $demande->procedures()->detach(); // facultatif si la relation existe

        $is_deleted = $demande->delete();

        if (!$is_deleted) {
            return response()->json([
                "success" => false,
                "error" => true,
                "data" => $is_deleted,
                "message" => "La suppression a échoué."
            ], 401);
        }

        return response()->json([
            "success" => true,
            "error" => false,
            "data" => $is_deleted,
            'message' => 'Demande supprimée avec succès',
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => true,
            'message' => "La suppression de la demande a échoué.",
            'exception' => $e->getMessage(), // utile en dev
        ], 500);
    }
}

  
   /*  public function destroy(Demande $demande)
    {
    try{   
    $is_deleted = $demande->delete();
        if($is_deleted != true){
            return response()->json([
                "success" => false,
                "error" => true,
                "data" => $is_deleted,
                "message" => "La suppression a échoué."
            ], 401);
        }
        return response()->json([
            "success" => true,
            "error" => false,
             "data" => $is_deleted,
            'message' => 'demande deleted successfully',
        ], 200);

        } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => true,
            'message' => "L'ajout de la procédure a échoué.",
            'exception' => $e->getMessage(), // utile en dev
        ], 500);
    }
        
    }
*/
}
//php artisan make:controller DemandeController --resource