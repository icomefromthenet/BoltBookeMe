<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Controller;

use DateTime;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Bolt\Storage\Database\Connection;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\SelectQueryHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\BetterResultSet;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\MemberEntity;

/**
 * The list and managment of schedule members and teams
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class WorkerController extends CommonController implements ControllerProviderInterface
{
    
    /**
     * Convert Member Id in url to entity
     * 
     */ 
    public function convertMemberIdToEntity($member, Request $request)
    {
        $app = $this->oContainer;
        
        if(true === empty($member)) {
            $app->abort(404, 'Member param must not be empty', []);
        }
        
        $oRepo = $this->getRepository('Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\MemberEntity');
    
        $oMember = $oRepo->find($member);
        
        if(true === empty($oMember)) {
            $app->abort(404,'Member at ID '.$member. ' has not been found');
        }
        
        return $oMember;
        
    }
    

    public function connect(Application $app)
    {
        /** @var $ctr \Silex\ControllerCollection */
        $oCtr = $app['controllers_factory'];

        $oCtr->get('',[$this,'onWorkerList'])
              ->bind('bookme-worker-list');
   
        $oCtr->get('search',[$this,'onWorkerSearch'])
             ->bind('bookme-worker-search');
      
        // Edit Existing Schedule Member (bookme-worker-view-basic)
      
        $oCtr->get('edit/{member}/basic',[$this,'onWorkerDetailsEdit'])
              ->convert('member', [$this,'convertMemberIdToEntity'])
              ->bind('bookme-worker-edit-basic');    
        
        $oCtr->post('edit/{member}/basic',[$this,'onWorkerDetailsSaveExisting'])
             ->convert('member', [$this,'convertMemberIdToEntity'])
             ->bind('bookme-worker-edit-basic-save');    
        
        // Create New Schedule Member (bookme-worker-create-basic)
        $oCtr->get('edit/basic',[$this,'onWorkerDetailsCreate'])
              ->bind('bookme-worker-create-basic');
              
        $oCtr->post('edit/basic',[$this,'onWorkerDetailsSaveNew'])
              ->bind('bookme-worker-create-basic-save');
              
          
            
        
        return $oCtr;
    }
    
    public function onWorkerSearch(Application $app, Request $request)
    {
        $oDatabase           = $this->getDatabaseAdapter();
        $oNow                = $this->getNow();
        $aConfig             = $this->getExtensionConfig();
        $oResult             = new BetterResultSet();
        $oForm               = $this->getForm('member.builder')->getForm();
        $aErrors             = [];
        
        $oForm->handleRequest($request);
    
        if ($oForm->isSubmitted() && $oForm->isValid()) {
            $aSearch = $oForm->getData();
            $oHandler = new SelectQueryHandler($this->oContainer);
            $oResult = $oHandler->executeQuery($oHandler->getQuery('member'),$aSearch);
        
        } else {
            
            $aErrors = [];
            foreach ($oForm->getErrors(true, true) as $formError) {
                $aErrors[] = $formError->getMessage();
            }
            
        }
        
        
            
        return $app->json(['results'=> $oResult->getAll(), 'errors' => $aErrors]);
        
        
    }

    /**
     * Load a page that list of schedule members
     *
     * @param Application   $app
     * @param Request       $request
     * @return Response
     */
    public function onWorkerList(Application $app, Request $request)
    {
       
       $oDatabase           = $this->getDatabaseAdapter();
        $oNow                = $this->getNow();
        $aConfig             = $this->getExtensionConfig();
        $oDataTable          = $this->getDataTable('member');
        $oSearchForm         = $this->getForm('member.builder')->getForm();
     
        //bind request vars to datatable data url
        $oDataTable->getOptionSet('AjaxOptions')->setRequestParams($request->query->all());

        //incude request params as values to our form
        $oSearchForm->handleRequest($request);
       
        $aData = [
            'oForm'         => $oSearchForm->createView(),    
            'title'         => 'Member List',
            'subtitle'      => 'Review Schedule Members',
            'sConfigString' => $oDataTable->writeConfig(), 
            'aEvents'       => $oDataTable->getEvents(),
        ];


        return $app['twig']->render('@BookMe/worker_list.twig', $aData, []);
    }


    /**
     * Load a page that allow create a new of a Members Basic Details, which
     * minimum info required for a new member
     *
     * @param Application   $app
     * @param Request       $request
     * @return Response
     */
    public function onWorkerDetailsCreate(Application $app, Request $request)
    {
        $oWorkerForm    = $this->getForm('memberdetails.builder')->getForm();
        
        
        // Build Data for View
        
        
        $aData = [
          'title' => 'Member Create',
          'subtitle' => 'Create new Member',
          'oForm' => $oWorkerForm->createView(),
            
        ];
        
        
         return $app['twig']->render('@BookMe/worker_basic_details_create.twig', $aData, []);
        
    }

    /**
     * Load a page that allow editing of a Members Basic Details
     *
     * @param Application   $app
     * @param Request       $request
     * @param MemberEntity  $oMember
     * @return Response
     */
    public function onWorkerDetailsEdit(Application $app, Request $request, MemberEntity $member)
    {
        
        $oWorkerForm    = $this->getForm('memberdetails.builder');
        $oMenu          = $this->getMenu('member');
        $oMember        = $member;
        
        
        // Bind Details into the form
        
        $oWorkerForm->setData([
            'iMembershipId' => $oMember->getMemberId(),
            'sMemberName'   => $oMember->getMemberName(),
            
        ]);
        
        
        // Build Data for View
        $this->bindMenuParameters($oMenu,['member' => $oMember->getMembershipId()]);
        
        $aData = [
          'title'            => 'Member Edit',
          'subtitle'         => 'Edit Membes Basic Details',
          'sMenuHeading'     => 'Member Actions',
          'oMenubuilder'     => $oMenu,
          'oForm'            => $oWorkerForm->getForm()->createView(),
          'oMember'          => $oMember,
        ];
        
        
        return $app['twig']->render('@BookMe/worker_basic_details_edit.twig', $aData, []);
        
    }
    
    
    public function onWorkerDetailsSaveNew(Application $app, Request $request)
    { 
        
        
    }


    public function onWorkerDetailsSaveExisting(Application $app, Request $request, MemberEntity $oMember)
    { 
        
        
    }

    
    /**
     * Create a new Schedule Member
     * 
     * @param Application   $app
     * @param Request       $request
     * @return Response 
     */ 
    public function onWorkerAdd(Application $app, Request $request)
    {
        
        
    }
    
    
    /**
     * Create a new schedule team
     * 
     * @param Application   $app
     * @param Request       $request
     * @return Response  
     */ 
    public function onTeamAdd(Application $app, Request $request)
    {
        
        
    }
    
    /**
     * Add a member to one or more teams
     * 
     * @param Application   $app
     * @param Request       $request
     * @return Response  
     */ 
    public function onAddRegisterMembertoTeam(Application $app, Request $request)
    {
        
        
    }
   
    /**
     * Remove a member from on or more teams
     * 
     * @param Application   $app
     * @param Request       $request
     * @return Response  
     */ 
    public function onWithdrawlMemberFromTeam(Application $app, Request $request)
    {
     
        
    }
    
}
/* End of Calendar Admin Controller */
