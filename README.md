# plugin-AdministradorUserBH

Clonando git clone https://github.com/dvdti/plugin-AdministratorUserBH.git

mv plugin-AdministratorUserBH AdministratorUserBH

Ativando em plugin.php, adicione na sua array de Plugins:

 'UserAdministratorBH' => ['namespace' => 'UserAdministratorBH'],



Redefinidos link dos termos de uso. Inclua no conf-base.php do tema.
  'auth.config' => [
        'urlTermsOfUse' => env('auth','termos-e-condicoes-de-uso'),
    ],


Funções do plugin
1- Define novo termo de uso
a) Criar um parametro de data que indica a partir de quando o novo aceite no termo será exigido.
b) Todos que logam no mapa sem ter aceitado os novos termos ou que tenham uma data de aceite mais antigo do que a data de atualização do termo são redirecionados a página do termo.
c) Assim que o usuário aceita o termo, é gravado um metadado com a data de aceite e o acesso as funções do mapa é liberado. 
d) A data do novo termo será refernta a variavel dateNewTerm do arquivo plugin.php

2- Possibilidade de bloquear, desbloquear e despublicar todos os conteúdos do usuário
a) Ao editar o perfil de um usuário aparecerá os botões:
 Bloquear usuário: Aparece se o usuário estiver ativi e muda o status do usário para -9.
 Desbloquear usuário: Aparece se o usuário estiver inativo com status -9 e muda o status para 1
 Despublicar conteúdos do usuário: Todos os agentes, espaços, eventos, projetos e oportunidades do usuário que estão publicados viram rascunho. 



