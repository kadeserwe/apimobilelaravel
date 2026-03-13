<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBailleurRequest;
use App\Http\Requests\UpdateBailleurRequest;
use Illuminate\Http\Request;

use App\Models\Bailleur;
//use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class BailleurController extends Controller
{
    //
    public function index()
    {
        //$bailleurs = Bailleur::all();
        $bailleurs = Bailleur::with('precedure')->get();

        return response()->json([
            'message' => 'Bailleurs retrieved successfully',
            'data' => $bailleurs,
            'total_page' => $bailleurs->count(),
        ]);
    }

    public function show($id)
    {
        $bailleur = Bailleur::findOrFail($id);
      
        return response()->json([
            'status' => true,
            'message' => 'Bailleurs retrieved successfully',
            'data' => $bailleur
        ], 200);
    }
    

    public function store(StoreBailleurRequest $request)
    {
        

        $validatedData = $request->all();
       //   $validatedData = $request->validated();

    // Vérifie si un fichier est uploadé
    if ($request->hasFile('fichier')) {
        $file = $request->file('fichier');

        // Donne un nom unique au fichier
        $filename = time() . '_' . $file->getClientOriginalName();

        // Sauvegarde dans storage/app/public/bailleurs
        $path = $file->storeAs('bailleurs', $filename, 'public');

        // Ajoute le chemin au tableau des données
        $validatedData['fichier'] = $path;
    }

    // Création en base
    $bailleur = Bailleur::create($validatedData);

    if (is_null($bailleur)) {
        return response()->json([
            "success" => false,
            "error" => true,
            "message" => "L'ajout de bailleur a échoué.",
        ], 401);
    }

    return response()->json([
        'message' => 'Bailleur créé avec succès',
        'data' => $bailleur,
    ], 201);
    }

    public function update(UpdateBailleurRequest $request, Bailleur $bailleur)
{
    $validatedData = $request->only(['libelle', 'description', 'fichier']);

    // Gestion du fichier
    if ($request->hasFile('fichier')) {
        if ($bailleur->fichier && Storage::disk('public')->exists($bailleur->fichier)) {
            Storage::disk('public')->delete($bailleur->fichier);
        }

        $filename = time() . '_' . $request->file('fichier')->getClientOriginalName();
        $path = $request->file('fichier')->storeAs('bailleurs', $filename, 'public');
        $validatedData['fichier'] = $path;
    }

    $bailleur->update($validatedData);

    return response()->json([
        'status'  => true,
        'message' => 'Bailleur mis à jour avec succès',
        'data'    => $bailleur
    ], 200);
}


    // public function update(Request $request, Bailleurs $bailleur)
    // {
    //     // Validation des données
    //     $validator = Validator::make($request->all(), [
    //         'libelle' => 'required|string|max:255',
    //         'description' => 'required',
    //     ]);
    //     ]);
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 400);
    //     }

        

    //     // Mise à jour des champs si définis
    //     if (isset($request->libelle)) {
    //         $bailleur->libelle = $request->libelle;
    //     }

    //     if (isset($request->description)) {
    //         $bailleur->description = $request->description;
    //     }

       
    //     $bailleur->save();

    //     return response()->json([
    //         'message' => 'Bailleur updated successfully',
    //         'data' => $bailleur,
    //     ]);
    // }

    public function destroy(Bailleur $bailleur)
{
    try {
        // 📁 Supprimer le fichier s’il existe dans le stockage
        if ($bailleur->fichier && Storage::disk('public')->exists($bailleur->fichier)) {
            Storage::disk('public')->delete($bailleur->fichier);
        }

        // 🗑️ Supprimer le bailleur de la base de données
        $bailleur->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Bailleur supprimé avec succès.',
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status'  => false,
            'message' => 'Erreur lors de la suppression du bailleur.',
            'error'   => $e->getMessage(),
        ], 500);
    }
}

    // public function destroy(Bailleur $bailleur)
    // {
    //     // Suppression du logo associé
        

       
    // $is_deleted = $bailleur->delete();
    //     if($is_deleted != true){
    //         return response()->json([
    //             "success" => false,
    //             "error" => true,
    //             "data" => $is_deleted,
    //             "message" => "La suppression a échoué."
    //         ], 401);
    //     }
    //     return response()->json([
    //         "success" => true,
    //         "error" => false,
    //          "data" => $is_deleted,
    //         'message' => 'Bailleur deleted successfully',
    //     ]);
    // }
}
