$(document).ready(function(){
    $('#check-term').click(function(){
        if($(this).prop('checked') == true){
            $('input[type="submit"]').prop('disabled', false);
            $('input[type="submit"]').css('background-color', '#6c6');
            $('input[type="submit"]').css('border', '1px solid #53c653');
            $('input[type="submit"]').css('color', '#fff');
        }else{
            $('input[type="submit"]').prop('disabled', true);
            $('input[type="submit"]').css('background-color', '#e2e2e2');
            $('input[type="submit"]').css('border', '1px solid #b3b3b3');
            $('input[type="submit"]').css('color', '#666');
        }
    });
   /* $('#user-managerment-adminBlockUser').click(function() {
        let email = $('#email-to-admin-set-block').val();
        $.post(MapasCulturais.baseURL + 'auth/adminblockuser', { email: email}, function(r){
            if(r.error) {
                alert(r.error);
                return;
            }
            location.reload();    
        });
    });
    $('#user-managerment-adminUnlockUser').click(function() {
        let email = $('#email-to-admin-set-unlock').val();
        $.post(MapasCulturais.baseURL + 'auth/adminunlockuser', { email: email}, function(r){
            if(r.error) {
                alert(r.error);
                return;
            }
            location.reload();
        });
    });
    $('#user-managerment-adminUnpublishContentUser').click(function() {
        let email = $('#email-to-admin-set-unpublish').val();
        $.post(MapasCulturais.baseURL + 'auth/adminunpublishcontentuser', { email: email}, function(r){
            if(r.error) {
                alert(r.error);
                return;
            }
            location.reload();
        });
    });
    $('#user-managerment-cancel').click(function(){
        MapasCulturais.Modal.close('#admin-block-user');
        MapasCulturais.Modal.close('#admin-unlock-user');
    });
    $('#user-managerment-cancel-unpublish').click(function(){
        MapasCulturais.Modal.close('#admin-unpublish-content-user');
    });
   /* $('#search-filter .submenu-dropdown li#user-blocked-filter').click(function(){
        let nome = $('#campo-de-busca').val();
        $.post(MapasCulturais.baseURL + 'auth/adminsearchuserblockerd', { nome: nome }, function(r){
            if(r.error) {
                alert(r.error);
                return;
            }
               //alert("id: " + r.users[0].userId + " nome:" + r.users[0].agentName + " usr: " +  r.users[0].agentId);
             var users   = [
                    {agentName:'John', agentId:25, userId:12},
                    {agentName:'John1', agentId:251, userId:121},
                    {agentName:'John2', agentId:252, userId:122},
                  ];
                //location.reload();
            return users;
            //location.reload();
        });

    });
  /*  (function(){
        var app = angular.module('ngRepeatUser',[]);
        app.controller('repeatController', function($scope) {
           //alert("id: " + r.users[0].userId + " nome:" + r.users[0].agentName + " usr: " +  r.users[0].agentId);
           $scope.users   = ['a','b','c'];
               /* {agentName:'John', agentId:25, userId:12},
                {agentName:'John1', agentId:251, userId:121},
                {agentName:'John2', agentId:252, userId:122},
              ];*/
            //location.reload();
       // });
   // });
    
});

    //location.reload();



