<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BandController;


// Registo de routes para registo, login e logout tratadas por Fortify em app/Providers/FortifyServiceProvider.php - registadas no método boot() quando precisam de uma view definida por nós.
// Docs: https://laravel.com/docs/11.x/fortify#authentication e https://laravel.com/docs/11.x/fortify#registration (nomenclatura mantida)
    
// Nota: fortify é headless. Retorna a view, mas como apresentamos essa view fica ao nosso critério. Possível usar com app como API, embora Sanctum ofereça um serviço mais apropriado e completo
// (para uma API de consumo interno). Mantido Fortify para efeitos de avaliação. Apenas foi mantida a feature para automatizar o registo de utilizadores.

// Nota 2: as routes geridas via Fortify têm nome: php artisan route:list
// A documentação aqui é anormalmente fraca. Só se consegue ver o nome das rotas, o controlador e o verbo http esperado. Para mais info, ver vendor/laravel/fortify/[...], em particular routes e [...]http/controllers

// Nota 3: para personalizar colunas da tabela users é necessário adicionar a criação do campo em app/Actions/Fortify/CreateNewUser.php. Neste caso estamos a forçar a criação de um user standard com o código 1. Admin terá
// código 0 e será inserido directamente na DB. Se quiséssemos inserir inputs, seria imperativo validá-los primeiro, no array passado a Validator::make(). Foi ainda necessário adicionar o campo 'user_type' ao array de fillables
// em app/Models/User.php
    
/*
Nota 4:

Para melhorar segurança e não expor identificadores da DB ao público (ver cheat sheet da OWASP https://cheatsheetseries.owasp.org/cheatsheets/Insecure_Direct_Object_Reference_Prevention_Cheat_Sheet.html#mitigation, melhor
    resposta em https://stackoverflow.com/questions/396164/exposing-database-ids-security-risk e qualquer outra pesquisa similar a "is it safe to expose database ids?") foi implementada uma coluna para uuid nas tabelas que precisam
    de uma referência aberta ao público. Uma colisão de uuid não é impossível, mas é suficientemente improvável nos standards recentes (uuid v4, rfc 4122) para poderem ser usados com segurança (desde que o sistema a gerar o uuid seja
    sempre o mesmo e não estejamos a falar de biliões de registos). Um uuid não é criptograficamente seguro e, para a nossa aplicação, não precisa de ser. Precisa apenas de ser suficientemente aleatório para ser único e não expor
    ao público dados da DB que podem ser usados para, por exemplo, fazer um scrape iterativo dos conteúdos, ou adivinhar o volume de armazenamento.
    
    A metodologia de implementação consiste no uso de Str::uuid() numa coluna 'uuid' indexada. Esta indexação representa uma penalização de performance da DB em acções de inserção e eliminação de registos que possuam esta coluna,
    embora seja relativamente consensual que o impacto no desempenho é compensado pelos benefícios do padrão (ver, por exemplo, https://itnext.io/laravel-the-mysterious-ordered-uuid-29e7500b4f8 e 
    https://www.reddit.com/r/PostgreSQL/comments/mi78aq/any_significant_performance_disadvantage_to_using/). A nossa implementação vai depender do tamanho da nossa DB. Se não for muito grande, não é necessário indexar os uuids e o
    impacto sobre o desempenho de lookups é negligenciável. Caso contrário, sofreremos o impacto, mas manteremos o benefício de segurança que a utilização deste registo fornece (nomeadamente, não expor ao público o tmaanho da nossa DB).
    
    Não é usado Str::orderedUuid() porque não será preciso ordenar nada com base nos uuids. Para isso existem as chaves primárias. Não usar esta forma de uuid tem ainda a vantagem de não expor o timestamp das operações INSERT, uma vez que,
    mais uma vez, o uuid não é criptograficamente seguro, mas sim simplesmente codificado, e o timestamp em orderedUuid() consta do início da string.
    
    Para os efeitos deste trabalho, foi indexada a coluna para presumir a pior performance possível (https://laravel.com/docs/11.x/migrations#available-index-types). Uma vez que não é chave, não é usado o trait 'hasUuids' (ver https://laravel.com/docs/11.x/eloquent#uuid-and-ulid-keys)
*/


Route::get('/', [BandController::class, 'index'])->name('home');
// Route::get('/show/{uuid}', [BandController::class, 'show'])->name('band.show'); // Desnecessário. Deixar o resource controller tratar do assunto. Routes criadas automaticamente.
// O controller não quer saber o que passamos a partir da view (por exemplo, para ver uma banda específica; só sabe que precisa de um argumento no método show()), nós é que decidimos o que queremos que apareça no url (no nosso caso, o uuid).
// Não conheço as melhores práticas para isto, mas já que os nossos objectos não são gigantescos vou passá-los inteiros da view para o controller e a partir daí decido o que preciso. Também evita um lookup à DB.
Route::resource('bands', BandController::class);