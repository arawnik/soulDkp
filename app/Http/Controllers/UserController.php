<?php

namespace ikitiera\Http\Controllers;

use Illuminate\Support\Facades\DB;

use ikitiera\Http\Requests;
use Illuminate\Http\Request;

use Auth;
use Validator;

class UserController extends Controller
{
	
	 /**
	 * Create a new controller instance. Sets the title and name of authenticated user into $data table.
	 *
	 * @return	void
	 */
	public function __construct() {
		//First we call parent
		parent::__construct();
		
		//Require authentication before going further.
		$this->middleware('auth');
		if (Auth::check()) {
			//Set default values for UserController data.
			$this->data['id'] = Auth::id();
			$this->data['name'] = Auth::user()->name;
		}
	}

	/**
	 * Show the controlpanel.
	 * TODO!
	 *
	 * @return	View	Controlpanel view that is showed to user.
	 */
    public function index() {
		//Fix title.
		$this->setTitlePage(trans('common.controlpanel'));
		
		return view('user.controlpanel', $this->data);
	}
	
	/**
	 * Shows list of raids that user can manage. Also has option to add new raid.
	 *
	 * @return	View 	Returns view with the specified options.
	 */
	public function raidManagement() {
		//Fix title.
		$this->setTitlePage(trans('common.raid_management'));
		
		//Lets fetch the data for our big table
		$raids =  DB::select('CALL get_raids');
		$this->data['raids'] = $raids;

		//simply return the home view using default values.
		return view('user.raids.raid_management', $this->data);
	}
	
	/**
	 * Shows the information of specified raid in a way it can be modified.  Also has option to add new items and adjustments and attendants to the raid.
	 *
	 * @param	integer	$id		Specifies the ID of the modified raid.
	 *
	 * @return	View 	Returns view with the specified options.
	 */
    public function modifySpecificRaid($id) {
		//Fix title.
		$this->setTitlePage(trans('management.modify_raid'));
		
		//Parse the id.
		$actualId = $this->checkId($id);
		$this->data['raid_id'] = $actualId;
		
		//Lets get data of the raid.
		$raid =  DB::select('CALL get_raid_data(:raid_id)',array('raid_id'=>$this->data['raid_id']));
		if(!isset($raid[0])) { //If there is no data, we go to not found.
			abort(404);
		} else { //Else set data for view.
			$this->data['raid'] = $raid[0];
		}
		
		//Lets get ALL characters.
		$characters =  DB::select('CALL get_characters');
		$this->data['characters'] = $characters;
		
		//Lets fetch all attendees to specific raid.
		$raidAttends =  DB::select('CALL get_raid_attends(:raid_id)',array('raid_id'=>$this->data['raid_id']));
		$this->data['raid_attends'] = $raidAttends;
		
		//We also need list of people who arent selected.
		$notSelected = array();
		foreach ($characters as $char) {
			$found = false;
			foreach ($raidAttends as $attend) {
				if ($char->char_id == $attend->char_id) {
					$found = true;
					break;
				}
			}
			if (!$found) {
				$notSelected[] = $char;
			}
		}
		$this->data['not_in_raid'] = $notSelected;
		
		//Lets also get items
		$raidItems =  DB::select('CALL get_raid_items(:raid_id)',array('raid_id'=>$this->data['raid_id']));
		$this->data['raid_items'] = $raidItems;
		
		//Lets also get adjustments
		$raidAdjustments =  DB::select('CALL get_raid_adjustments(:raid_id)',array('raid_id'=>$this->data['raid_id']));
		$this->data['raid_adjustments'] = $raidAdjustments;
		
		return view('user.raids.modify_raid', $this->data);
	}
	
	/**
	 * Updates the information of raid. Doesnt include items/adjustments/attendance.
	 *
	 * @param	Request	$request	Includes the information sent in form.
	 *
	 * @return	redirect 		Redirects back to modifying the raid by ID.
	 */
	public function updateRaid(Request $request) {
		$validator = Validator::make($request->all(),[
			'raid_id' => 'required|integer',
			'value' => 'required|integer',
			'comment' => 'required',
			'date' => 'required|date_format:Y-m-d',
		]);
		//If we didnt pass, we can return.
		if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();
		
		//We have the values, we can get them into variables.
		$raidId = $this->checkId($request->get('raid_id'));
		$value = $request->get('value');
		$comment = $request->get('comment');
		$date = $request->get('date');
		
		//Update data in database.
		$raidUpdated = DB::select('CALL update_raid_data(:raid_id,:value,:comment,:date)',array('raid_id'=>$raidId, 'value'=>$value, 'comment'=>$comment, 'date'=>$date));
		
		$request->session()->flash('alert-success', trans('management.modified_raid_info'));
		return redirect()->back()->withInput();
	}
	
	/**
	 * Adds raid depending the POST request that has been sent.
	 *
	 * @param	Request	$request	The request that specifies the information.
	 *
	 * @return	Redirect	Redirects to the info of the added raid
	 */
	public function addRaid(Request $request) {
		$validator = Validator::make($request->all(),[
			'value' => 'required|integer',
			'comment' => 'required',
			'date' => 'required|date_format:Y-m-d',
		]);
		//If we didnt pass, we can return.
		if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();
		
		//Fetch all values, since they passed.
		$value = $request->get('value');
		$comment = $request->get('comment');
		$date = $request->get('date');
		
		//Lets insert into database and select the added id.
		$addedId = DB::select('SELECT add_Raid(:value,:comment,:date) as added_id',array('value'=>$value, 'comment'=>$comment, 'date'=>$date))[0]->added_id;
		
		return redirect()->route('modify_raid/{id}', ['id' => $addedId]);
	}
	
	/**
	 * Deletes a raid.
	 *
	 * @param	Request	$request	The request that specifies the raid that will be deleted.
	 *
	 * @return	Redirect	Redirects back to where the delete was called.
	 */
	public function deleteRaid(Request $request) {
		//Check the ID.
		$raidId = $this->checkId($request->get('raid_id'));
		//Delete the raid.
		$raidDeleted =  DB::select('CALL delete_raid(:raid_id)',array('raid_id'=>$raidId));
		//Return and tell info.
		$request->session()->flash('alert-success', trans('management.deleted_raid'));
		return redirect()->back();
	}
	
	/**
	 * Modifies the attendance linked to raid specified in $request
	 *
	 * @param	Request	$request	The request that specifies the information.
	 *
	 * @return	Redirect	Redirects back with info
	 */
	public function modifyRaidAttendance(Request $request) {
		$validator = Validator::make($request->all(),[
			'selected_chars' => 'required', //TODO: Needs better checks..
		]);
		//If we didnt pass, we can return.
		if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();
		
		//Get variables.
		$raidId = $this->checkId($request->get('raid_id'));
		$selectedChars = $request->get('selected_chars');
		
		//Delete old and add new ones.
		$deletedAttends =  DB::select('CALL delete_attends_from_raid(:raid_id)',array('raid_id'=>$raidId));
		if(!empty($selectedChars)) {
			foreach ($selectedChars as $char) {
				$deletedAttends =  DB::select('CALL add_attends_to_raid(:raid_id, :char_id)',array('raid_id'=>$raidId, 'char_id'=>$char));
			}
		}
		
		$request->session()->flash('alert-success', trans('management.modified_raid_attendance'));
		return redirect()->back();
	}
	
	/**
	 * Adds item to linked raid specified in $request
	 *
	 * @param	Request	$request	The request that specifies the information.
	 *
	 * @return	Redirect	Redirects back.
	 */
	public function addRaidItem(Request $request) {
		$validator = Validator::make($request->all(),[
			'raid_id' => 'required|integer',
			'character' => 'required|integer',
			'use_amount' => 'required|integer',
			'use_desc' => 'required',
		]);
		//If we didnt pass, we can return.
		if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();
		
		//Get Variables
		$raidId = $this->checkId($request->get('raid_id'));
		$character = $request->get('character');
		$value = $request->get('use_amount');
		$desc = $request->get('use_desc');
		
		//Add to Database
		$usageAdded =  DB::select('CALL add_point_usage(:raid_id, :char_id, :use_value, :use_desc)',array('raid_id'=>$raidId, 'char_id'=>$character, 'use_value'=>$value, 'use_desc'=>$desc));
		//Set up message and return.
		$request->session()->flash('alert-success', trans('management.added_raid_item'));
		return redirect()->back();
	}
	
	/**
	 * Deletes item specified in $request
	 *
	 * @param	Request	$request	The request that specifies the information.
	 *
	 * @return	Redirect	Redirects to the info of the modified raid
	 */
	public function deleteRaidItem(Request $request) {
		$raidId = $this->checkId($request->get('raid_id'));
		$usageId = $this->checkId($request->get('item_id'));
		//Delete the usage.
		$raidDeleted =  DB::select('CALL delete_usage(:usage_id)',array('usage_id'=>$usageId));
		
		$request->session()->flash('alert-success', trans('management.deleted_raid_item'));
		return redirect()->back();
	}
	
	/**
	 * Adds adjustment to linked raid specified in $request
	 *
	 * @param	Request	$request	The request that specifies the information.
	 *
	 * @return	Redirect	Redirects to the info of the modified raid
	 */
	public function addRaidAdjustment(Request $request) {
		$validator = Validator::make($request->all(),[
			'raid_id' => 'required|integer',
			'adjust_character' => 'required|integer',
			'adjust_value' => 'required|integer',
			'adjust_comment' => 'required',
		]);
		//If we didnt pass, we can return.
		if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();
		
		$raidId = $this->checkId($request->get('raid_id'));
		$character = $request->get('adjust_character');
		$amount = $request->get('adjust_value');
		$desc = $request->get('adjust_comment');
		
		//Check if has adjustment.
		$charAdj =  DB::select('CALL char_raid_adjustment(:raid_id, :char_id)',array('raid_id'=>$raidId, 'char_id'=>$character));
		if(count($charAdj) > 0) { //If there is no data, we set up error and return.
			$validator->errors()->add('has_adjustment', trans('management.has_adjustment'));
			return redirect()->back()->withErrors($validator)->withInput();
		}
		//If we didnt return, we add the adjustment.
		$usageAdded =  DB::select('CALL add_adjustment(:raid_id, :char_id, :adjust_value, :adjust_comment)',array('raid_id'=>$raidId, 'char_id'=>$character, 'adjust_value'=>$amount, 'adjust_comment'=>$desc));
		
		$request->session()->flash('alert-success', trans('management.added_raid_adjustment'));
		return redirect()->back();
	}
	
	/**
	 * Deletes the adjustment specified in $request
	 *
	 * @param	Request	$request	The request that specifies the information.
	 *
	 * @return	Redirect	Redirects to the info of the modified raid
	 */
	public function deleteRaidAdjustment(Request $request) {
		$raidId = $this->checkId($request->get('raid_id'));
		$charId = $this->checkId($request->get('char_id'));
		
		//Delete the adjustment.
		$raidDeleted =  DB::select('CALL delete_adjustment(:raid_id, :char_id)',array('raid_id'=>$raidId, 'char_id'=>$charId));
		
		$request->session()->flash('alert-success', trans('management.deleted_raid_adjustment'));
		return redirect()->route('modify_raid/{id}', ['id' => $raidId]);
	}
	
	/**
	 * Shows list of normalizations that user can manage. Also has option to add new normalizations.
	 *
	 * @return	View 	Returns view with the specified options.
	 */
	public function normalizationManagement() {
		//Fix title.
		$this->setTitlePage(trans('common.normalization_management'));
		
		$this->data['id'] = Auth::id();
		
		//Lets fetch the data for our big table
		$normalizations =  DB::select('CALL get_normalizations');
		$this->data['normalizations'] = $normalizations;

		//simply return the home view using default values.
		return view('user.normalization.normalization_management', $this->data);
	}
	
	/**
	 * Adds normalization instance and adds given percent normalization to every character in table.
	 *
	 * @param	Request	$request	The request that specifies the information.
	 *
	 * @return	Redirect	Redirects back to decay management.
	 */
	public function addNormalization(Request $request) {
		$validator = Validator::make($request->all(),[
			'percent' => 'required|integer',
			'comment' => 'required',
		]);
		//If we didnt pass, we can return.
		if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();
		
		$user = $request->get('user');
		$percent = $request->get('percent');
		$comment = $request->get('comment');
		
		//Lets insert into database and select the added id.
		$addedId = DB::select('SELECT add_normalization(:user,:percent,:comment) as added_id',array('user'=>$user, 'percent'=>$percent, 'comment'=>$comment))[0]->added_id;
		
		//Lets get ALL characters for decay adding.
		$characters =  DB::select('CALL get_characters');
		$this->data['characters'] = $characters;
		
		//And lets call the procedure to add decay to every character
		foreach ($characters as $char) {
			DB::insert('CALL calculate_character_normalization(:normalization_id,:char_id,:percent)', ['normalization_id'=>$addedId, 'char_id'=>$char->char_id, 'percent'=>$percent]);
		}
		
		$request->session()->flash('alert-success', trans('management.added_normalization'));
		return redirect()->route('normalization_management');
	}
	
	/**
	 * Deletes the normalization specified in $request
	 *
	 * @param	Request	$request	The request that specifies the information.
	 *
	 * @return	Redirect	Redirects back to decay management.
	 */
	public function deleteNormalization(Request $request) {
		$normalizationId = $this->checkId($request->get('normalization_id'));
		
		//Delete the normalization.
		$normDeleted =  DB::select('CALL delete_normalization(:normalization_id)',array('normalization_id'=>$normalizationId));
		
		return redirect()->route('normalization_management');
	}
	
	/**
	 * Fetches the latest normalization information and displays interface to recalculate characters.
	 *
	 * @return	View 	Returns view with the specified options.
	 */
	public function modifyLatestNormalization(Request $request) {
		//Fix title.
		$this->setTitlePage(trans('management.modify_latest_normalization'));
		
		//Lets get data of the latest normalization.
		$normalization =  DB::select('CALL get_latest_normalization');
		if(!isset($normalization[0])) { //If there is no data, we go to not found.
			$request->session()->flash('alert-danger', trans('management.no_normalization'));
			return redirect()->route('normalization_management');
		}
		
		$this->data['normalization'] = $normalization[0];
		$normalizationId = $this->data['normalization']->normalization_id;
		//Lets get ALL points of our normalization.
		$normPoints =  DB::select('CALL get_specific_normalization_points(:normalization_id)',array('normalization_id'=>$normalizationId));
		$this->data['normalization_points'] = $normPoints;
		
		return view('user.normalization.modify_latest_normalization', $this->data);
	}
	
	/**
	 * Updates the points for specific character in normalization.
	 *
	 * @param	Request	$request	Includes the information sent in form.
	 *
	 * @return	redirect 		Redirects back to modifying the latest normalization.
	 */
	public function updateNormalizationPoints(Request $request) {
		$validator = Validator::make($request->all(),[
			'normalization_id' => 'required|integer',
			'char_id' => 'required|integer',
		]);
		//If we didnt pass, we can return.
		if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();
		
		//We have the values, we can get them into variables.
		$normalizationId = $this->checkId($request->get('normalization_id'));
		$charId = $this->checkId($request->get('char_id'));
		
		//Get character data and check if exists
		$characterData =  DB::select('CALL get_character_data(?)',array($charId));
		if(!isset($characterData[0])) { //If there is no data, we go to not found.
			$request->session()->flash('alert-danger', trans('management.no_character'));
			return redirect()->back()->withInput();
		}
		$character = $characterData[0];
		
		//Get normalization values, check that it exists.
		$normalizationData =  DB::select('CALL get_normalization(:normalization_id)',array('normalization_id'=>$normalizationId));
		if(!isset($normalizationData[0])) { //If there is no data, we go to not found.
			$request->session()->flash('alert-danger', trans('management.no_normalization'));
			return redirect()->back()->withInput();
		}
		$normalization = $normalizationData[0];
		$percent = $normalization->normalization_percent;
		
		//First delete old data
		$raidUpdated = DB::select('CALL delete_character_normalization(:normalization_id,:char_id)',array('normalization_id'=>$normalizationId, 'char_id'=>$charId));
		//then calculate new.
		DB::insert('CALL calculate_character_normalization(:normalization_id,:char_id,:percent)', ['normalization_id'=>$normalizationId, 'char_id'=>$charId, 'percent'=>$percent]);
		
		$request->session()->flash('alert-success', trans('management.recalculated_normalization', ['name' => $character->char_name]));
		return redirect()->back()->withInput();
	}
	
	/**
	 * Shows list of characters that user can manage. Also has option to add new character.
	 *
	 * @return	View 	Returns view with the specified options.
	 */
	public function characterManagement() {
		//Fix title.
		$this->setTitlePage(trans('common.character_management'));
		
		//Lets get ALL characters for decay adding.
		$characters =  DB::select('CALL get_characters');
		$this->data['characters'] = $characters;
		
		//Need to also get classes and roles to give options for character creation.
		$classes =  DB::select('CALL get_classes');
		$this->data['classes'] = $classes;
		$roles =  DB::select('CALL get_roles');
		$this->data['roles'] = $roles;
		
		return view('user.character.character_management', $this->data);
	}
	
	/**
	 * Adds new character.
	 *
	 * @param	Request	$request	The request that specifies the information.
	 *
	 * @return	Redirect	Redirects back to character management.
	 */
	public function addCharacter(Request $request) {
		$validator = Validator::make($request->all(),[
			'name' => 'required',
			'class' => 'required|integer',
			'role' => 'required|integer',
		]);
		//If we didnt pass, we can return.
		if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();
		
		$name = $request->get('name');
		$class = $request->get('class');
		$role = $request->get('role');
		
		//And lets add the character.
		$charAdded =  DB::select('CALL add_character(:name, :class, :role)',array('name'=>$name, 'class'=>$class, 'role'=>$role));
		
		$request->session()->flash('alert-success', trans('management.added_character'));
		return redirect()->route('character_management');
	}
	
	/**
	 * Updates the information of character.
	 *
	 * @param	Request	$request	Includes the information sent in form.
	 *
	 * @return	redirect 		Redirects back to character management
	 */
	public function updateCharacter(Request $request) {
		$validator = Validator::make($request->all(),[
			'char_id' => 'required|integer',
			'name' => 'required',
			'class' => 'required|integer',
			'role' => 'required|integer',
		]);
		//If we didnt pass, we can return.
		if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();
		
		$charId = $this->checkId($request->get('char_id'));
		$name = $request->get('name');
		$class = $request->get('class');
		$role = $request->get('role');
		
		//Update data in database.
		$charUpdated = DB::select('CALL update_character(:char_id,:name,:class,:role)',array('char_id'=>$charId, 'name'=>$name, 'class'=>$class, 'role'=>$role));
		
		$request->session()->flash('alert-success', trans('management.updated_character', ['name' => $name]));
		return redirect()->route('character_management');
	}
	
	/**
	 * Deletes the character specified in $request
	 *
	 * @param	Request	$request	The request that specifies the information.
	 *
	 * @return	Redirect	Redirects back to decay management.
	 */
	public function deleteCharacter(Request $request) {
		$charId = $this->checkId($request->get('char_id'));
		
		//Delete the character.
		$charDeleted =  DB::select('CALL delete_character(:char_id)',array('char_id'=>$charId));
		
		$request->session()->flash('alert-success', trans('management.deleted_character'));
		return redirect()->route('character_management');
	}
	
	/**
	 * Shows the information of specified character in a way it can be modified.
	 *
	 * @param	integer	$id		Specifies the ID of the modified character.
	 *
	 * @return	View 	Returns view with the specified options.
	 */
	public function modifySpecificCharacter($id) {
		//Fix title.
		$this->setTitlePage(trans('management.modify_character'));
		
		//Parse the id.
		$actualId = $this->checkId($id);
		$this->data['char_id'] = $actualId;
		
		//Lets get data of the character.
		$character =  DB::select('CALL get_character_data(:char_id)',array('char_id'=>$this->data['char_id']));
		if(!isset($character[0])) { //If there is no data, we go to not found.
			abort(404);
		} else { //Else set data for view.
			$this->data['character'] = $character[0];
		}
		
		//Need to also get classes and roles to give options to modify character data.
		$classes =  DB::select('CALL get_classes');
		$this->data['classes'] = $classes;
		$roles =  DB::select('CALL get_roles');
		$this->data['roles'] = $roles;
		
		return view('user.character.modify_character', $this->data);
	}
}