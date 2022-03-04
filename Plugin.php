<?php
namespace UserAdministratorBH;
use MapasCulturais\App;
use MapasCulturais\i;
use MapasCulturais\Entities\Agent;
use MapasCulturais\Entities\Event;
use MapasCulturais\Entities\Opportunity;
use MapasCulturais\Entities\Project;
use MapasCulturais\Entities\Space;



//require_once 'Controllers/TermUser.php';

class Plugin extends \MapasCulturais\Plugin {
	public static $dateAcceptTermsMetadata = "dateAcceptTerms";
	public static $dateBlockUserMetadata = "dateBlockUser";
	
	public static $dateNewTerm = '2022/03/04 14:11:00';

    public function _init() {
        // enqueue scripts and styles
        $app = App::i();
	   // $plugin = $this;
		// Load JS & CSS
      /*  $app->hook('<<GET|POST>>(auth.<<*>>)', function() use ($app) {
           
            $app->view->enqueueScript('app', 'userAdministratorBH', 'js/userAdministratorBH.js');
            $app->view->enqueueStyle('app', 'userAdministratorBH','css/userAdministratorBH.css');
            //$app->view->enqueueStyle('app', 'fontawesome', 'https://use.fontawesome.com/releases/v5.8.2/css/all.css');
        });*/



        // add hooks
        $app->hook('template(<<*>>):end', function() use ($app) {
           	if(!$app->user->is('guest') && !self::checkAcceptedTerms() ){
				if("$_SERVER[REQUEST_URI]" != "/autenticacao/termos-e-condicoes-de-uso/" ){ 
					$urlTerm = $app->createUrl('auth', 'termos-e-condicoes-de-uso');
					$app->redirect($urlTerm);				
				}	
			}
        });
         
        $app->hook('GET(auth.termos-e-condicoes-de-uso)',function () use ($app) {
			$app->view->enqueueScript('app', 'userAdministratorBH', 'js/userAdministratorBH.js');
            $app->view->enqueueStyle('app', 'userAdministratorBH','css/userAdministratorBH.css');
            $this->render('termos-e-condicoes-de-uso',[
				'form_term_action' => $app->createUrl('auth', 'form-accepted-term'),
			]);
        });        
        $app->hook('POST(auth.form-accepted-term)',function () use ($app) {
			$acceptTermUse = ! empty($app->request->post('acceptTermUse')) ? true : false; 
			if($acceptTermUse) {
				self::setDateAcceptTerms();
				$app->applyHook('auth.successful');
				$redirectUrl = $app->request->post('redirectUrl') ?: $app->auth->getRedirectPath();
				$app->applyHookBoundTo($this, 'auth.successful:redirectUrl', [&$redirectUrl]);
				unset($_SESSION['mapasculturais.auth.redirect_path']);
				$app->redirect($redirectUrl);	
			}else{
				$app->auth->logout();
				$app->redirect($app->auth->getRedirectPath());
			}	
        });
		$app->hook('POST(auth.adminblockuser)',function () use ($app) {
                      
            $email = $this->data['email'];
            $user = $app->auth->getUserFromDB($email);
            $user->status = -9; 
			$user->setMetadata(self::$dateBlockUserMetadata, date('d/m/Y à\s H:i:s'));
            
            // save
            $app->disableAccessControl();
            $user->saveMetadata(true);
            $app->enableAccessControl();
            $user->save(true);
            $app->em->flush();

            $this->json (array("status"=>-9,"user"=>$user));
			
        });
		$app->hook('POST(auth.adminunlockuser)',function () use ($app) {
                      
            $email = $this->data['email'];
            $user = $app->auth->getUserFromDB($email);
            $user->status = 1; 
			$user->setMetadata(self::$dateBlockUserMetadata,'');
            
            // save
            $app->disableAccessControl();
            $user->saveMetadata(true);
            $app->enableAccessControl();
            $user->save(true);
            $app->em->flush();

            $this->json (array("status"=>1,"user"=>$user));
			
        });
        $app->hook('adminblockuser', function ($userEmail,$userStatus) use($app){

            if(!$app->user->is('admin')) {
                return;
            }
			$app->view->enqueueScript('app', 'userAdministratorBH', 'js/userAdministratorBH.js');
			            
			if($userStatus == 1){
				echo
				'
				   	<a class="btn btn-danger js-open-dialog" data-dialog="#admin-block-user" data-dialog-block="true">
                		Bloquear usuário
            		</a>
					<div id="admin-block-user" class="js-dialog" title="Bloqueio de usuário">
                		<label for="admin-set-block-user">Confirmar bloqueio do usuário '.$userEmail.'?</label><br>
                		<input type="hidden" id="email-to-admin-set-block" value='.$userEmail.' />
                		<button class="btn btn-danger" id="user-managerment-cancel" style="float:right;" > Cancelar </button>
						<button class="btn  btn-warning" id="user-managerment-adminBlockUser"  style="float:left;"> Bloquear </button>
            		</div>
            	';
			}else if($userStatus == -9){
				echo
				'
					<a class="btn btn-danger js-open-dialog" data-dialog="#admin-unlock-user" data-dialog-block="true">
						Desbloquear usuário
					</a>
					<div id="admin-unlock-user" class="js-dialog" title="Desbloqueio de  usuário">
                		<label for="admin-set-unlock-user">Confirmar o desbloqueio do usuário '.$userEmail.'?</label><br>
                		<input type="hidden" id="email-to-admin-set-unlock" value='.$userEmail.' />
                		<button class="btn btn-danger" id="user-managerment-cancel"  style="float:right;" > Cancelar </button>
						<button class="btn  btn-warning" id="user-managerment-adminUnlockUser" style="float:left;" > Desbloquear </button>
            		</div>
            	';
			}	

        });
		$app->hook('POST(auth.adminunpublishcontentuser)',function () use ($app) {
                      
            $email = $this->data['email'];
            $user = $app->auth->getUserFromDB($email);
            
			//despublicando agentes
			$agents = $user->enabledAgents;
			foreach($agents as $agent):
    			$agent->status = 0;
				$agent->save(true);
				$app->em->flush();    
			endforeach;
			$events = $user->enabledEvents;
			foreach($events as $event):
    			$event->status = 0;
				$event->save(true);
				$app->em->flush();    
			endforeach;
			$spaces = $user->enabledSpaces;
			foreach($spaces as $space):
    			$space->status = 0;
				$space->save(true);
				$app->em->flush();    
			endforeach;
			$opportunities = $user->enabledOpportunities;
			foreach($opportunities as  $opportunity):
    			$opportunity->status = 0;
				$opportunity->save(true);
				$app->em->flush();    
			endforeach;            
			$projects = $user->enabledProjects;
			foreach($projects as $project):
    			$project->status = 0;
				$project->save(true);
				$app->em->flush();    
			endforeach;
          
			
        });
		$app->hook('adminunpublishcontentuser', function ($userEmail) use($app){

            if(!$app->user->is('admin')) {
                return;
            }
			$app->view->enqueueScript('app', 'userAdministratorBH', 'js/userAdministratorBH.js');
			            
			echo
			'
			   	<a class="btn btn-danger js-open-dialog" data-dialog="#admin-unpublish-content-user" data-dialog-block="true" style="float:right;">
               		Despublicar conteúdos do usuário
            	</a>
				<div id="admin-unpublish-content-user" class="js-dialog" title="Despublicar todos os conteúdo do usuário">
               		<label for="admin-set-unpublish-user">
					   Atenção, todas as publicações do usuário se tornarão rasacunho. Se necessário republicar, só poderá ser feito um por um. <br/> <br/>
					   Confirmar bloqueio do usuário '.$userEmail.'?</label><br>
               		<input type="hidden" id="email-to-admin-set-unpublish" value='.$userEmail.' /><br/>
               		<button class="btn btn-danger" id="user-managerment-cancel-unpublish" style="float:right;"> Cancelar </button>
					<button class="btn  btn-warning" id="user-managerment-adminUnpublishContentUser" style="float:left;"> Despublicar </button>
            	</div>
            ';
				
			
        });
    }

    public function register() {
        // register metadata, taxonomies
        $this->registerUserMetadata(self::$dateAcceptTermsMetadata, ['label' => i::__('Data de aceite do termo de uso')]);
		$this->registerUserMetadata(self::$dateBlockUserMetadata, ['label' => i::__('Data de bloqueio do usuário')]);

    }
    public static function checkAcceptedTerms(){
		$app = App::i();
		$user = $app->user;
		
		//$dateTerm = $app->config['app.updateTermsUser'];
		$dateAccept = $app->user->getMetadata(self::$dateAcceptTermsMetadata); 
		if( empty($dateAccept) || (strtotime(self::$dateNewTerm) >= strtotime($dateAccept))){
			return false;	
		}
		return true;	

	}

	public static function setDateAcceptTerms(){
		$app = App::i();
		$user = $app->user;
		$app->user->setMetadata(self::$dateAcceptTermsMetadata, date('Y/m/d H:i:s'));
		$app->disableAccessControl();
		$app->user->saveMetadata();
		$app->enableAccessControl(); 
		
	}

}