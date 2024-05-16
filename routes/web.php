<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BandController;

/*
Registo de routes para registo, login e logout tratadas por Fortify em app/Providers/FortifyServiceProvider.php - registadas no método boot() quando precisam de uma view definida por nós.
    Docs: https://laravel.com/docs/11.x/fortify#authentication e https://laravel.com/docs/11.x/fortify#registration (nomenclatura mantida)
    
Nota: fortify é headless. Retorna a view, mas como apresentamos essa view fica ao nosso critério. Possível usar com app como API, embora Sanctum ofereça um serviço mais apropriado e completo
    (para uma API de consumo interno). Mantido Fortify para efeitos de avaliação. Apenas foi mantida a feature para automatizar o registo de utilizadores.

    !!! O campo user_type, no array $fillable, em App\Models\User não pode ser mass assignable, sob pena de exposição completa a uma injecção do parâmetro no acto de criação de um utilizador. Mudar o valor user_type !!!
    !!! deve ser feito explicitamente por um utilizador administrador. O valor não deve ser atribuído no acto de criação de um utilizador. Deixar o valor por defeito que a DB cria. Ver https://laravel.com/docs/11.x/eloquent#mass-assignment !!!

Nota 2: as routes geridas via Fortify têm nome: php artisan route:list
    A documentação aqui é fraca. Só se consegue ver o nome das rotas, o controlador e o verbo http esperado. Para mais info, ver vendor/laravel/fortify/[...], em particular routes e [...]http/controllers.
    Parece propositado, no sentido de que o funcionamento interno destes mecanismos não é relevante para o utilizador.

Nota 3: para personalizar colunas da tabela users é necessário adicionar a criação do campo em app/Actions/Fortify/CreateNewUser.php. Neste caso estamos a forçar a criação de um user standard com o código 1. Admin terá
    código 0 e será manipulado directamente na DB. Se quiséssemos inserir inputs, seria imperativo validá-los primeiro, no array passado a Validator::make().

Nota 4:

Para melhorar segurança e não expor identificadores da DB ao público (ver cheat sheet da OWASP https://cheatsheetseries.owasp.org/cheatsheets/Insecure_Direct_Object_Reference_Prevention_Cheat_Sheet.html#mitigation, melhor
    resposta em https://stackoverflow.com/questions/396164/exposing-database-ids-security-risk e qualquer outra pesquisa similar a "is it safe to expose database ids?") foi implementada uma coluna para uuid nas tabelas que precisam
    de uma referência aberta ao público. Uma colisão de uuid não é impossível, mas é suficientemente improvável nos standards recentes (uuid v4, rfc 4122) para poderem ser usados com segurança (desde que o sistema a gerar o uuid seja
    sempre o mesmo e não estejamos a falar de biliões de registos). Um uuid não é criptograficamente seguro e, para a nossa aplicação, não precisa de ser. Precisa apenas de ser suficientemente aleatório para ser único e não expor
    ao público dados da DB que podem ser usados para, por exemplo, fazer um scrape iterativo dos conteúdos, ou adivinhar o volume de armazenamento.
    
    A metodologia de implementação consiste no uso de Str::uuid() numa coluna 'uuid' indexada. Esta indexação representa uma penalização de performance da DB em acções de inserção e eliminação de registos que possuam esta coluna,
    embora seja relativamente consensual que o impacto no desempenho é compensado pelos benefícios do padrão (ver, por exemplo, https://www.reddit.com/r/PostgreSQL/comments/mi78aq/any_significant_performance_disadvantage_to_using/).
    A nossa implementação vai depender do tamanho da nossa DB. Se não for muito grande, não é necessário indexar os uuids e o impacto sobre o desempenho de lookups é negligenciável.
    Caso contrário, sofreremos o impacto, mas manteremos o benefício de segurança que a utilização deste registo fornece (nomeadamente, não expor ao público ids usados na DB, ou pistas sobre o volume de
    armazenamento da nossa DB).
    
    Não é usado Str::orderedUuid() (ver, por exemplo, https://itnext.io/laravel-the-mysterious-ordered-uuid-29e7500b4f8) porque não será preciso ordenar nada com base nos uuids. Para isso existem as chaves primárias. Não usar esta
    forma de uuid tem ainda a vantagem de não expor o timestamp das operações INSERT, uma vez que, mais uma vez, o uuid não é criptograficamente seguro, mas sim meramente codificado, e o timestamp em orderedUuid() consta do início da string.
    
    Para os efeitos deste trabalho, foi indexada a coluna uuid para presumir a pior performance possível (https://laravel.com/docs/11.x/migrations#available-index-types). Uma vez que não é chave, não é usado o trait 'hasUuids'
    (ver https://laravel.com/docs/11.x/eloquent#uuid-and-ulid-keys)
*/



/*
Criamos o resource controller sem as routes que não vão precisar de middleware para verificar autenticação. Por outras palavras, todas as routes agrupadas abaixo serão controladas pelo middleware 'auth'.
As que não precisem deste controlo ficam necessariamente definidas *depois do grupo*, sob pena de colisão de rotas (excepto para '/'). Isto parece ir contra a documentação 
(ver https://laravel.com/docs/11.x/controllers#restful-supplementing-resource-controllers)
mas atentemos ao facto de não estarmos a *adicionar* uma rota para além das por defeito, mas sim a condicionar uma já existente. Se a rota bands.show constar antes do grupo condicionado por middleware,
ao tentarmos invocar o método get() na rota bands.create (URI: /bands/create), será antes invocado o método get() na rota bands.show (URI /bands/{uuid}).
Apenas podemos presumir que uma colisão no sentido oposto não acontece porque, na definição do grupo de rotas validado por middleware, excluímos especificamente o método show().

Nota: criar o resource controller dentro do grupo de routes não é exemplificado na documentação. É teste nosso, mas parece funcionar como esperado. Registar uma route única retorna um objecto \Illuminate\Routing\Route. Registar
um resource controller retorna um objecto \Illuminate\Routing\PendingResourceRegistration. O método group() da classe RouteRegistrar parece preparado para lidar com o assunto.

*/

Route::middleware(['can:create,App\Models\User', 'auth'])->group(function () { // middleware 'can' usa a UserPolicy em App\Policies\UserPolicy. Escrever o nosso seria fácil. O desafio está em usar as ferramentas que já vêm com a framework.
    Route::resource('bands', BandController::class)->except([
        'index', 'show'
    ]);    
});
Route::get('/', [BandController::class, 'index'])->name('home');
Route::get('/bands/{uuid}', [BandController::class, 'show'])->name('bands.show');

/*
Nota 5:

Embora tenha criado as routes todas por si, os métodos do resource controller não querem saber o que passamos como argumento. Por exemplo, para ver uma banda específica, embora o método index() já tenha um argumento $id,
isto é apenas por conveniência; podemos passar e usar qualquer argumento para continuar a executar a lógica que queremos. Para mostrar uma banda, o controller só sabe que precisa de um argumento no método show(), nós é que
decidimos o que queremos que apareça no url (no nosso caso, o uuid, não o id).

Nota 6:
Em BandController::index() e outros semelhantes crio um array com todas as nossas bandas. Isto é, admissivelmente, péssima prática, devendo usar-se algum tipo de chunking para evitar carregar potencialmente milhares de registos para a memória do nosso
servidor ao mesmo tempo mas, para os efeitos deste exercício, passa (espero eu).

Quando invocamos o método show() do controller, o que passamos a partir do botão para ver a banda deve ser o uuid, não o objecto $band, uma vez que a framework vai presumir que estamos a passar o id do objecto e este torna-se exposto no URL:
Não consigo explicar se se trata de route model binding, implicit binding, ou outro mecanismo
*/

/*
Nota: O método de armazenamento é tão desastroso quanto a documentação existente.
Descobrimos como armazenar ficheiros fora do directório 'storage', configurando um novo disk e fornecendo assim um URL mais user friendly e não anunciando tão explicitamente que a aplicação usa esta framework,
mas vemo-nos forçados a acrescentar o directório em que os ficheiros são guardados manualmente quando lhes queremos aceder através da facade Storage
(ex: Storage::url("images/".$band->cover_image))
*/
