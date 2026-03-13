<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProcedureRequest;
use Illuminate\Http\Request;

use App\Models\Procedure;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProcedureController extends Controller
{
    //
   /* public function index()
    {
        $procedure = Procedure::with('bailleur','demandes')->get();

        return response()->json([
            'message' => 'procedure retrieved successfully',
            'data' => $procedure,
            'total_page' => $procedure->count(),
        ]);
    }
*/
   public function index(Request $request)
    {
        try {
            $paginate = filter_var($request->query('pagination', true), FILTER_VALIDATE_BOOLEAN);
            $perPage  = (int) $request->query('per_page', 10);

            $query = Procedure::with('bailleur', 'demandes');

            // 🔍 Filtre par libelle
            if ($request->filled('libelle')) {
                $libelle = $request->libelle;
                $query->where('libelle', 'LIKE', '%' . $libelle . '%');
            }

            // 🎯 Filtre par bailleur_id
            if ($request->filled('bailleur_id')) {
                $query->where('bailleur_id', $request->bailleur_id);
            }

            // 🎯 Filtre par demande_id (many-to-many)
            if ($request->filled('demande_id')) {
                $query->whereHas('demandes', function ($q) use ($request) {
                    $q->where('demandes.id', $request->demande_id);
                });
            }

            if (!$paginate) {
                $procedures = $query->get();
                return response()->json([
                    'status' => true,
                    'message' => 'Procédures récupérées avec succès',
                    'data' => $procedures,
                    'total' => $procedures->count(),
                ]);
            }

            $procedures = $query->paginate($perPage);
            return response()->json([
                'status' => true,
                'message' => 'Procédures récupérées avec succès',
                'data' => $procedures->items(),
                'meta' => [
                    'current_page' => $procedures->currentPage(),
                    'size_page' => $procedures->perPage(),
                    'total' => $procedures->total(),
                    'total_pages' => $procedures->lastPage(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => true,
                'message' => "La récupération des procédures a échoué",
                'exception' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $procedure = Procedure::with('bailleur','demandes')->findOrFail($id);
      
        return response()->json([
            'status' => true,
            'message' => 'Procedure retrieved successfully',
            'data' => $procedure
        ], 200);
    }

    public function store(ProcedureRequest $request)
{
    $validatedData = $request->all();

    try {
        // Création de la procédure (sans les IDs des demandes)
        $procedure = Procedure::create($validatedData);

        // Création de la relation many-to-many avec les demandes
        if (isset($validatedData['demande_ids'])) {
            $procedure->demandes()->attach($validatedData['demande_ids']);
        }

        return response()->json([
            'message' => 'Procédure créée avec succès',
            'data' => $procedure->load('demandes'),
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

  /*  public function store(ProcedureRequest $request)
    {
        
        $validatedData = $request->all();
    try {
        $procedure = Procedure::create($validatedData);
        // Creation de la relation many-to-many
        if (isset($validatedData['demande_ids'])) {
            // attach() ajoute des enregistrements dans la table pivot.
            $procedure->demandes()->attach($validatedData['demande_ids']);
        }

        return response()->json([
            'message' => 'Procedure created successfully',
            'data' => $procedure,
        ], 201);
        } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => true,
            'message' => "L'ajout de la procédure a échoué.",
            'exception' => $e->getMessage(), // utile en dev
        ], 500);
    }
    }*/
      public function update(ProcedureRequest $request, Procedure $procedure)
  {
    $validatedData = $request->all();

    $procedure->update($validatedData);

    // Mise à jour de la relation many-to-many
    if (isset($validatedData['demande_ids'])) {
        $procedure->demandes()->sync($validatedData['demande_ids']);
    }

    return response()->json([
        'status' => true,
        'message' => 'Procédure mise à jour avec succès',
        'data' => $procedure->load('demandes'),
    ], 200);
}

   /* public function update(ProcedureRequest $request, Procedure $procedure)
    {
        $procedure -> update($request->all());

         // Creation de la relation many-to-many
        if (isset($procedure['demande_ids'])) {
            // attach() modifier des enregistrements dans la table pivot.
            $procedure->demandes()->sync($procedure['demande_ids']);
        }
        return response()->json([
            'status' => true,
            'message' => 'Procedure retrieved successfully',
            'data' => $procedure
        ], 200);
       
     }*/

     public function destroy(Procedure $procedure)
    {
    $procedure->demandes()->detach(); // facultatif si la relation existe  
    $is_deleted = $procedure->delete();
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
            'message' => 'Procedure deleted successfully',
        ], 200);
    }

}
