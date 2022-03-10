<article class="objeto clearfix" ng-repeat="user in users" id="user-result-{{user.userId}}" >
  <div class="objeto-header">
    <h1>{{user.agentName}} </h1>
    <div class="objeto-header-actions">
      <a class="btn btn-default icon icon-user" href="<?php echo $app->createUrl('panel', 'userManagement')?>/?userId={{user.userId}}">Info</a>
    </div>
  </div>
</article>
