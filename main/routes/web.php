<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\CategorieArticleController;
use \App\Http\Controllers\ArticleController;
use \App\Http\Controllers\CommentaireController;
use \App\Http\Controllers\MedecinController;
use \App\Http\Controllers\CentreController;
use \App\Http\Controllers\ServiceController;
use \App\Http\Controllers\ReclamationsController;
use \App\Http\Controllers\ReponseReclamationController;
use \App\Http\Controllers\NoteController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\PatientController;

use App\Http\Controllers\ficheController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('/client/client');
})->name('home');

Route::get('/admin', function () {
    return view('/admin/layout');
});

Route::middleware(['auth', 'user-access:admin'])->group(function () {
    Route::resource('categorieArticle', CategorieArticleController::class);
    Route::resource('article', ArticleController::class);

    Route::get('/reclamation/search/{statut}', [ReclamationsController::class, 'FindReclamationByStatut']);
    Route::get('/admin/medecin',[MedecinController::class,'showadmin']);
    Route::delete('/admin/medecin/delete/{medecin}',[MedecinController::class,'destroy']);
    Route::get('/admin/confirmermedecin/{medecin}',[MedecinController::class,'confirmer']);
    Route::resource('produit', ProduitController::class);
    Route::resource('categories', CategorieController::class);
    Route::resource('centres',CentreController::class);
    Route::resource('reponse', ReponseReclamationController::class);
    Route::get('/listeReclamations', [ReclamationsController::class, 'index']);

});




Route::get('/article/FindArticlesByCatFront/{cat}', [ArticleController::class, 'FindArticlesByCatFront']);
Route::get('/article/supprimerRecherche/{cat}', [ArticleController::class, 'supprimerRecherche']);
Route::middleware(['auth', 'user-access:user'])->group(function () {

    Route::get('/article/showFront/{a}', [ArticleController::class, 'showFront']);

    Route::post('/commentaire/supprimer/{commentaire}/{a}', [CommentaireController::class, 'supprimer'])->name("commentaire.supprimer");
    Route::post('/commentaire/ajouter/{a}', [CommentaireController::class, 'ajouter']);
    Route::post('/commentaire/modifier/{a}/{commmentaire}', [CommentaireController::class, 'update']);

    Route::post('/note/ajouter/{a}', [NoteController::class, 'ajouter']);
    Route::post('/note/modifier/{a}/{note}', [NoteController::class, 'update']);
    Route::post('/note/supprimer/{note}/{a}', [NoteController::class, 'supprimer'])->name("note.supprimer");

    Route::resource('reclamations', ReclamationsController::class);
    Route::get('/listeReclamation', [ReclamationsController::class, 'indexfront']);
    Route::get('/detailReclamation/{reclamation}', [ReponseReclamationController::class, 'show'])->name("reponse.show");

    Route::resource('medecin', MedecinController::class);




});
Route::middleware(['auth', 'user-access:admin'])->group(function () {
Route::resource('services',ServiceController::class);
Route::get('services/create2/{id}',function($id){
    return view("service.create",['centre_id'=>$id]);
});
});
Route::middleware(['auth', 'user-access:medecin'])->group(function () {
    Route::get('/espacemedecin/mesinfromations',[MedecinController::class,'edit']);
    Route::get('/espacemedecin',[MedecinController::class,'show']);

    Route::patch('/espacemedecin/mesinfromation/edit/{medecin}',[MedecinController::class,'update']);
    Route::resource('patient', PatientController::class);
    Route::resource('fiche',ficheController::class);
    Route::get('/pdfview/{idpatient}',[PatientController::class,'pdfview'])->name('pdfview');

    
});

Route::get('/produitfront/{id}',[ProduitController::class,'indexFront']);
Route::get('/showmycenter/{userid}', [CentreController::class, 'showusercenter'])->name('showmycenter');
Route::get('/editcenter/{id}', [CentreController::class, 'editcenter'])->name('editcenter');
Route::patch('/updatecenter/{id}', [CentreController::class, 'updatecenter'])->name('updatecenter');
Route::post('/storeservice/', [ServiceController::class, 'storeservice'])->name('storeservice');
Route::get('/editservice/{id}', [ServiceController::class, 'editservice'])->name('editservice');
Route::patch('/updateservice/{id}', [ServiceController::class, 'updateservice'])->name('updateservice');
Route::delete('/destroyservice/{id}', [ServiceController::class, 'destroyservice'])->name('destroyservice');
Route::get('/listcentres', [CentreController::class, 'listcentres'])->name('listcentres');
Route::get('/usercentreshow/{id}', [CentreController::class, 'getrcenterbyid'])->name('usercentreshow');
Route::get('/exportword/{id}', [CentreController::class, 'exportword'])->name('exportword');
Route::get('createcenterservice/{id}',function($id){
    return view("service.createservice",['centre_id'=>$id]);
});
Auth::routes();

#Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
