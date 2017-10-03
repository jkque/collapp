<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Product;
use App\ProductOnHand;
use App\OnHandDetails;
use App\ProductDisburse;
use App\DisburseDetails;
use App\Debt;
use App\Collection;
use Image;
use Validator;
use Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class ApiController extends Controller
{
	use AuthenticatesUsers;


    public function login(Request $request)
    {
        if(Auth::attempt(['username' => request('username'), 'password' => request('password')])){
            $user = $this->guard()->user();
            $user->generateToken();
            return response()->json(['user' => $user->toArray()], 200);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    	
    }

    public function logout(Request $request)
	{
	    $user = Auth::guard('api')->user();

	    if ($user) {
	        $user->api_token = null;
	        $user->save();
	    }

	    return response()->json(['message' => 'User logged out.'], 200);
	}

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
		    return response()->json(['error'=>$validator->errors()], 400);            
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
                return response()->json(['user'=>$user],200);
            } else {
                return response()->json(['existing_users'=>$existing_list],206);
            }
        } else {
            $user->save();
            return response()->json(['user'=>$user],200);
        }
    }

    public function addEditProduct(Request $request)
    {

        $product = Product::find($request->id) ?? new Product;
        $validator = Validator::make($request->all(), [
			'name'         => 'required|max:255',
			'description'  => 'required|max:255',
			'price'        => 'required|numeric',
			'unit_id'      => 'required',
		]);
		if ($validator->fails()) {
		    return response()->json(['error'=>$validator->errors()], 400);            
		}

        if(!$request->has('id')) {
            $request->merge([
                'code'  => 'sampleProductCode',
            ]);
        }

        $product->fill($request->all());
        $product->save();
        return response()->json(['product'=>$product],200);
    }

    public function assignedProductToCollector(Request $request)
    {

        $onHand = new ProductOnHand;
        $validator = Validator::make($request->all(), [
			'collector_id' => 'required',
		]);

		if ($validator->fails()) {
		    return response()->json(['error'=>$validator->errors()], 400);            
		}

        $onHand->fill($request->all());
        $onHand->save();

        if($request->has('product_id')) {
        	for ($i=0; $i < count($request->product_id); $i++) { 
        		$prod = new OnHandDetails;
        		$prod->poh_id = $onHand->id;
        		$prod->product_id = $request->product_id[$i];
        		$prod->quantity = $request->quantity[$i];
        		$prod->save();
        	}
        }

        return response()->json(['data'=>$onHand->load('details.product','collector')],200);
    }

    public function disburseProduct(Request $request)
    {

        $disburse = new ProductDisburse;
        $validator = Validator::make($request->all(), [
			'collector_id' => 'required',
			'debtor_id'    => 'required',
		]);

		if ($validator->fails()) {
		    return response()->json(['error'=>$validator->errors()], 400);            
		}

        $disburse->fill($request->all());
        $disburse->save();

        if($request->has('product_id')) {
        	for ($i=0; $i < count($request->product_id); $i++) { 
        		$prod = new DisburseDetails;
        		$prod->pd_id = $disburse->id;
        		$prod->product_id = $request->product_id[$i];
        		$prod->quantity = $request->quantity[$i];
        		$prod->save();
        	}
        }

    	$debt = new Debt;
    	$debt->debtor_id = $request->debtor_id;
    	$debt->principal_amount = $disburse->load('details.product')->details->pluck('product.price')->sum();
    	$debt->term = $request->term;
    	$debt->details = $request->details;
    	$debt->status = 0;
    	$debt->save();


        return response()->json(['disburse'=>$disburse->load('details.product'),'debt'=>$debt->load('debtor')],200);
    }

    public function collectPayment(Request $request)
    {

        $payment = new Collection;
        $validator = Validator::make($request->all(), [
			'collector_id' => 'required',
			'debt_id'      => 'required',
			'amount'       => 'required|numeric',
		]);

		if ($validator->fails()) {
		    return response()->json(['error'=>$validator->errors()], 400);            
		}
		$payment->debt_id = $request->debt_id;
        $payment->fill($request->all());
        $payment->save();
        return response()->json(['data'=>$payment->load('debt.debtor')],200);
    }




    private function similarUsersExists($fname, $lname, $agency_id) {
        return User::where([['first_name', $fname], ['last_name', $lname], ['agency_id', $agency_id]])->get();
    }
    
}
