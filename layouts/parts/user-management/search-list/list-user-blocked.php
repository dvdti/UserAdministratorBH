<header id="user-list-header" class="entity-list-header clearfix" ng-show="data.global.filterEntity == 'user'">
  <h1><span class="icon icon-user"></span> <?php \MapasCulturais\i::_e("Usuários bloqueados");?></h1>
</header>

<div id="lista-dos-usuarios" class="lista usuario" infinite-scroll="data.global.filterEntity === 'user' && addMore('user')" ng-show="data.global.filterEntity === 'user'">
  <?php $this->part('user-management/search-list/list-user-blocked-item'); ?>
  <span ng-show="spinnerShow" class="clearfix">
    <img src="<?php $this->asset('img/spinner.gif') ?>" />
    <span><?php \MapasCulturais\i::_e("obtendo usuários bloqueados..."); ?></span>
  </span>
</div>