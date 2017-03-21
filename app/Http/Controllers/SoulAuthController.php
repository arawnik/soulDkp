<?php
namespace ikitiera\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Auth;
use Validator;

class SoulAuthController extends Controller
{
	/**
     * Show the Login screen.
     *
     * @return Response
     */
	public function LoginView()
	{
		return view('auth.login');
	}
	
    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
		$validator = Validator::make($request->all(),[
			'name' => 'bail|required',
			'password' => 'required',
		]);
		
		$name = $request->get('name');
		$password = $request->get('password');
		
		if (Auth::attempt(['name' => $name, 'password' => $password])) {
            // Authentication passed...
            return redirect()->route('home');
        } else {
			// redirect our user back to the form with the errors from the validator
			$validator->errors()->add('login', trans('auth.failed'));
			
			return redirect()->back()->withErrors($validator)->withInput();
		}
    }
}