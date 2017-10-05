<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use Validator;
use Hash;
use App\User;

class UserController extends Controller
{

    public function addEditUser(Request $request)
    {

        $user = User::find($request->id) ?? new User;
        $validator = Validator::make($request->all(), [
			'first_name'     => 'required|max:255',
			'last_name'	     => 'required|max:255',
			'address'        => 'required',
			'contact_number' => 'required',
		]);
		if ($validator->fails()) {
			return [
				'success' => false,
				'errors' => $validator->errors(),
			];            
		}

        $thumb = "";
        if ($request->image) {

            $name = str_random(20) . time() . '.jpg';
            $base64 = substr($request->image, strpos($request->image,',')+1);

            // This saves the base64encoded destinationPath
            file_put_contents(public_path() . '/uploads/' . $name, base64_decode($base64));

            $file = public_path() . '/uploads/' . $name;
            $thumb = 'thumb_' . $name;

            $image = Image::make($file)->encode('jpg')->orientate()->fit(200)->save(public_path() . '/uploads/' . $thumb);

            $user->image = '/uploads/' . $name;
        }

        if(!$request->has('id')) {
            $request->merge([
                'status'  => Hash::make('welcome'.$request->first_name),
                'username'  => generateUsername($request->first_name, $request->last_name),
                'status'  => 1,
            ]);
        }

        $user->fill($request->all());

        $existing_list = $this->similarUsersExists($request->first_name, $request->last_name, $request->agency_id);
        if (!$request->has('id')) {
            if($existing_list->count() == 0) {
                $user->save();
                return [
                	'success' => true,
                	'user' => $user,
                	'message' => 'User successfully added!',
                ];
            } else {
                return [
                	'success' => false,
                	'user' => $existing_list,
                	'existing_users' => true,
                ];
            }
        } else {
            $user->save();
            return [
            	'success' => true,
            	'user' => $user,
            	'message' => 'User successfully updated!',
            ];
        }
    }

    public function delete(Request $request)
    {
    	User::find($request->id)->delete();
    	return [
    		'success' => true,
    		'message' => 'User successfully deleted!',
    	];
    }
	
}
