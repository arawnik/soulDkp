<?php

namespace ikitiera\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;

/**
 * HomeController is the Controller that handles all of the DKP related requests for un-authenticated user.
 *
 * @author Jere Junttila <junttila.jere@gmail.com>
 * @license GPL
 */
class HomeController extends Controller
{	
    /**
     * Show the Dkp dashboard. Dashboard includes the data of all characters.
     *
     * @return Response
     */
    public function index()
    {
		//Fix title.
		$this->setTitlePage(trans('common.dashboard'));
		
		//Lets fetch the data for our character table including alot of combined data.
		$characters =  DB::select('CALL get_characters');
		$this->data['characters'] = $characters;
		
		//And return view with the data.
		return view('public.dashboard', $this->data);
    }
	
	/**
     * Show the listing of all raids.
	 *
	 * @return Response
     */
    public function raidList()
    {
		//Fix title.
		$this->setTitlePage(trans('common.raids'));
		
		//Lets fetch the data for our raids table
		$raids =  DB::select('CALL get_raids');
		$this->data['raids'] = $raids;
		
		//And return view with the data.
        return view('public.raids', $this->data);
    }
	
	/**
     * Show some statistic information about raids and dkp usage.
	 *
	 * @return Response
     */
    public function statsList()
    {
		//Fix title.
		$this->setTitlePage(trans('common.stats'));
		
		//Lets fetch the data of attendance to raids.
		$attendances =  DB::select('CALL get_attendance');
		$this->data['attendances'] = $attendances;
		
		//And return view with the data.
        return view('public.stats', $this->data);
    }
	
	/**
     * Show information of specific raid.
	 *
	 * @param  int  $id  Id of the raid that will be displayed.
	 * @return Response
     */
    public function specificRaid($id)
    {
		//Fix title.
		$this->setTitlePage(trans('common.raid'));
		
		//Parse the id.
		$actualId = $this->checkId($id);
		//Store the ID to $data for later usage.
		$this->data['raid_id'] = $actualId;
		
		//Lets check if the raid exists.
		$raidData = DB::select('CALL get_raid_data(:id)',['id' => $this->data['raid_id']]);
		if(!isset($raidData[0])) { //If there is no data, we go to not found.
			abort(404);
		} else { //Else set data for view.
			$this->data['raid_data'] = $raidData[0];
		}
		
		//Lets fetch the characters that attended to raid.
		$raidAttends =  DB::select('CALL get_raid_attends(:id)',['id' => $this->data['raid_id']]);
		$this->data['raid_attends'] = $raidAttends;
		//And also get items for the raid.
		$raidItems =  DB::select('CALL get_raid_items(:id)',['id' => $this->data['raid_id']]);
		$this->data['raid_items'] = $raidItems;
		//And also adjustments to the base dkp values for raid.
		$raidAdjustments =  DB::select('CALL get_raid_adjustments(:id)',['id' => $this->data['raid_id']]);
		$this->data['raid_adjustments'] = $raidAdjustments;
		
		//And return view with the data.
        return view('public.raid', $this->data);
    }
	
	/**
     * Show information of specific character.
	 *
	 * @param  int  $id  Id of the character that will be displayed.
	 * @return Response
     */
    public function specificCharacter($id)
    {
		//Fix title.
		$this->setTitlePage(trans('common.character'));
		
		//Parse the id.
		$actualId = $this->checkId($id);
		//Store the ID to $data for later usage.
		$this->data['char_id'] = $actualId;
		
		//Lets check if the char exists.
		$charData = DB::select('CALL get_character_data(:id)',['id' => $this->data['char_id']]);
		if(!isset($charData[0])) { //If there is no data, we go to not found.
			abort(404);
		} else { //Else set data for view.
			$this->data['char_data'] = $charData[0];
		}
		
		//Lets fetch the raids of the character.
		$raidAttends =  DB::select('CALL get_character_raids(?)',array($this->data['char_id']));
		$this->data['raids_attended'] = $raidAttends;
		//And also fetch items of the character.
		$items =  DB::select('CALL get_character_items(?)',array($this->data['char_id']));
		$this->data['items'] = $items;
		
		//And return view with the data.
        return view('public.character', $this->data);
    }
}
