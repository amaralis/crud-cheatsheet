<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BandController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\AlbumController;

Route::middleware(['auth'])->group(function () {
    // Middleware 'can' usa a UserPolicy em App\Policies\UserPolicy. Escrever o nosso seria fácil.
    // O desafio está em usar as ferramentas que já vêm com a framework, o que facilitará actualizações, alterações e manutenção mais tarde.
    // "band" e "album" foram route-bound no boot() de AppServiceProvider aos respectivos modelos através do uuid (yay security)
    // O segundo argumento do middleware 'can' pode não se introduzir, uma vez que o segundo parâmetro na UserPolicy é null. Como só pede um Model, também serve para qualquer outro modelo

    Route::get('/bands/create', [BandController::class, 'create'])->name('bands.create')->middleware('can:create,\App\Models\User,band');
    Route::post('/bands', [BandController::class, 'store'])->name('bands.store')->middleware(['can:create,\App\Models\User,band']);
    Route::get('/bands/{band}/edit', [BandController::class, 'edit'])->name('bands.edit')->middleware('can:edit,\App\Models\User,band');
    Route::put('/bands/{band}', [BandController::class, 'update'])->name('bands.update')->middleware('can:update,\App\Models\User,band');
    Route::delete('/bands/{band}', [BandController::class, 'destroy'])->name('bands.destroy')->middleware('can:delete,\App\Models\User,band');

    Route::get('/albums/create/{band}', [AlbumController::class, 'create'])->name('albums.create')->middleware(['can:create,\App\Models\User,album']);
    Route::post('/albums', [AlbumController::class, 'store'])->name('albums.store')->middleware(['can:create,\App\Models\User,album']);
    Route::get('/albums/{album}', [AlbumController::class, 'edit'])->name('albums.edit')->middleware(['can:edit,\App\Models\User,album']);
    Route::put('/albums/{album}', [AlbumController::class, 'update'])->name('albums.update')->middleware(['can:update,\App\Models\User,album']);
    Route::delete('/albums/{album}', [AlbumController::class, 'destroy'])->name('albums.destroy')->middleware(['can:delete,\App\Models\User,album']); // Vamos apenas fazer hard deletes

    Route::get('/songs/create/{album}', [SongController::class, 'create'])->name('songs.create')->middleware(['can:create,\App\Models\User,song']);
    Route::post('/songs', [SongController::class, 'store'])->name('songs.store')->middleware(['can:create,\App\Models\User,song']);
    Route::delete('/songs/{song}', [SongController::class, 'destroy'])->name('songs.destroy')->middleware(['can:delete,\App\Models\User,song']); // Vamos apenas fazer hard deletes
    Route::get('/songs/{song}', [SongController::class, 'edit'])->name('songs.edit')->middleware(['can:edit,\App\Models\User,song']);
    Route::put('/songs/{song}', [SongController::class, 'update'])->name('songs.update')->middleware(['can:update,\App\Models\User,song']);
});

Route::get('/', [BandController::class, 'index'])->name('home');
Route::get('/bands/{band}', [BandController::class, 'show'])->name('bands.show');

// Redireccionar para a página anterior com mensagem de erro (não implementado). Redireccionar para uma página qualquer sem feedback é mau UX, mas o código seria este:
// Route::fallback(function () {
//     return redirect()->route('home');
// });

/* ================================================================ NOTAS ================================================================

correr `php artisan migrate --seed --seeder=BandSeeder` para popular a base de dados. Variáveis disponíveis no seeder para alterar número de bandas, álbuns e canções criadas.

Registo de routes para registo, login e logout tratadas por Fortify em app/Providers/FortifyServiceProvider.php - registadas no método boot() quando precisam de uma view definida por nós.
    Docs: https://laravel.com/docs/11.x/fortify#authentication e https://laravel.com/docs/11.x/fortify#registration (nomenclatura mantida)
    
Nota: fortify é headless. Retorna a view, mas como apresentamos essa view fica ao nosso critério. Possível usar com app como API, embora Sanctum ofereça um serviço mais apropriado e completo
    (para uma API de consumo interno). Mantido Fortify para efeitos de avaliação. Apenas foi mantida a feature para automatizar o registo de utilizadores.

    !!! O campo user_type, no array $fillable, em App\Models\User não pode ser mass assignable, sob pena de exposição completa a uma injecção do parâmetro no acto de criação de um utilizador !!!
    !!! Ver https://laravel.com/docs/11.x/eloquent#mass-assignment !!!

- Para personalizar colunas da tabela users é necessário adicionar a criação do campo em app/Actions/Fortify/CreateNewUser.php. Neste caso estamos a forçar a criação de um user standard com o código 1. Admin terá
    código 0 e será manipulado directamente na DB. Se quiséssemos inserir inputs, seria imperativo validá-los primeiro, no array passado a Validator::make().

- Para melhorar segurança e não expor identificadores da DB ao público (ver cheat sheet da OWASP https://cheatsheetseries.owasp.org/cheatsheets/Insecure_Direct_Object_Reference_Prevention_Cheat_Sheet.html#mitigation, melhor
    resposta em https://stackoverflow.com/questions/396164/exposing-database-ids-security-risk e qualquer outra pesquisa similar a "is it safe to expose database ids?") foi implementada uma coluna para uuid nas tabelas que precisam
    de uma referência aberta ao público. Uma colisão de uuid não é impossível, mas é suficientemente improvável nos standards recentes (uuid v4, rfc 4122) para poderem ser usados com segurança (desde que o sistema a gerar o uuid seja
    sempre o mesmo e não estejamos a falar de biliões de registos). Um uuid não é criptograficamente seguro e, para a nossa aplicação, não precisa de ser. Precisa apenas de ser suficientemente aleatório para ser único e não expor
    ao público dados da DB que podem ser usados para, por exemplo, fazer um scrape iterativo dos conteúdos, ou adivinhar o volume de armazenamento.
    
    A metodologia de implementação consiste no uso de uuid() numa coluna 'uuid' indexada por defeito, mas não ordenada, uma vez que para ordenar registos usaríamos o id. O Laravel não permite a criação de campos únicos sem os indexar.
    Esta indexação representa uma penalização de performance da DB em acções de inserção e eliminação de registos que possuam esta coluna, embora seja relativamente consensual 
    que o impacto no desempenho é compensado pelos benefícios do padrão (ver, por exemplo, https://www.reddit.com/r/PostgreSQL/comments/mi78aq/any_significant_performance_disadvantage_to_using/).
    
    Uma vez que uuid não é chave, não é usado o trait 'hasUuids' (ver https://laravel.com/docs/11.x/eloquent#uuid-and-ulid-keys).

- Criaram-se as rotas inicialmente como Route::resource. Já se tinha investigado a questão e visto várias opiniões na internet que vão no sentido de, se precisarmos de middleware,
    acabam por se criar tantas excepções que mais vale criar cada rota individualmente. Confirma-se.

- Em BandController::index() e outros semelhantes crio um array com todas as nossas bandas. Isto é, admissivelmente, péssima prática, devendo usar-se algum tipo de chunking
    para evitar carregar potencialmente milhares de registos para a memória do nosso servidor ao mesmo tempo mas, para os efeitos deste exercício, passa (espero eu).
    Naturalmente, estamos também a ignorar necessidades de paginação.

- Quando uma Blade necessita de passar informação sobre um modelo ao controller, o que passamos a partir de um input escondido deve ser o uuid. Pesquisa-se o modelo no servidor, e o Laravel automatiza completamente
a tarefa via route model binding, desde que se tenha feito um bind explícito do uuid ao modelo desejado no método boot() de AppServiceProvider.
*/

