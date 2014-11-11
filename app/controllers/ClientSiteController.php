<?php
// app/controlers/ClientSiteController.php

class ClientSiteController extends BaseController

{
    public function showClients()
{
                $clients = ClientSite::all();
                return View::make('index')->with('clients',$clients);
}
//returns client and client feature info for profile
                public function clientProfile($siteName)
{   //select * from clientSites where siteName = $siteName
                $clientsite = ClientSite::whereSitename($siteName)->first();    
                $clientID = $clientsite['clientID'];//getting clientID for use in join query
//queries for retrieving features of each group for the selected client
                $clientFeatures = DB::table('features')->join('clientFeatures', function($join) use($clientID)
{
                $join->on( 'clientFeatures.featureID', '=', 'features.featureID')   
                ->where('clientFeatures.clientID', '=', $clientID);
})->get();
                $groupIDs = array();
                foreach ($clientFeatures as $c) {
                $groupIDs[] = $c->groupID;
}
                return View::make('profiles.clientProfiles')->with('clientsite',$clientsite)
                ->with('groupIDs',$groupIDs)
                ->with('clientFeatures',$clientFeatures);   
}   
         
  public function create()
         {
                return View::make('edit.create');
         }
         
         public function edit( $clientsite )
        
         {
                return View::make('edit.edit', compact('clientsite'));
         }
         public function saveCreate()
         {
                $input = Input::all();
                
                $clientsite = new ClientSite;
                $clientsite->siteName = $input['siteName'];
                $clientsite->description = $input['description'];
                $clientsite->launchDate = $input['launchDate'];
                $clientsite->save();
             
                
                //ClientSite::whereSiteName($clientsite->siteName)->first();
                ClientSite::where('siteName', $clientsite->siteName)->first();
                $clientID = $clientsite['clientID'];//getting clientID for use in join query
                            //queries for retrieving features of each group for the selected client
                $clientFeatures = DB::table('features')->join('clientFeatures', function($join) use($clientID)
{
                $join->on( 'clientFeatures.featureID', '=', 'features.featureID')
                ->where('clientFeatures.clientID', '=', $clientID);
                })->get();
                $groupIDs = array();
                foreach ($clientFeatures as $c) {
                $groupIDs[] = $c->groupID;
}
                return View::make('profiles.clientProfiles')->with('clientsite',$clientsite)
                ->with('groupIDs',$groupIDs)
                ->with('clientFeatures',$clientFeatures);  
                
         
            }
         public function show($clientID)
         {
            $clientsite = Clientsite::find($clientID);
            return View::make('clientsite', compact('clientsite'));
         }
}
