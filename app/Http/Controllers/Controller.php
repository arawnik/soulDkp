<?php

namespace ikitiera\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


/**
 * The generated Controller where other controllers are extended from.
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	
	/**
	 * Data array to be passed for views.
	 *
	 * @example "$this->data['title'] = 'new title';" Add title value to $data.
	 */
	public $data = array();
	
	/**
	 * Site name that will be displayed at the page title.
	 */
	public $sitename;
	
	/**
     * Create a new controller instance. Set default values to $data.
     *
     * @return void
     */
    public function __construct()
    {
		app()->setLocale('en');
		$this->sitename = trans('common.default_title');
		
        //Set default values for Controller data.
		$this->data = array(
			'title' => $this->sitename,
		);
    }
	
	/**
	 * Sets page prefix/postfix into variable that includes page title.
	 *
	 * @param int $id Id that we want to check.
	 *
	 * @return int Returns the int value of ID if it was present, otherwise abort(404)
	 */
	public function setTitlePage($pageName) {
		$this->data['title'] = $this->sitename .' - '. $pageName;
	}
	
	/**
	 * Checks if $id includes valid ID, if not redirects to 404.
	 *
	 * @param int $id Id that we want to check.
	 *
	 * @return int Returns the int value of ID if it was present, otherwise abort(404)
	 */
	public function checkId($id) {
		$idInt = intval($id, 10);
		if(!is_int($idInt) || $idInt <= 0) { //If there wasnt positive ID, 404.
			abort(404);
		} else {
			return $idInt;
		}
	}
}
