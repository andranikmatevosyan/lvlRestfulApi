<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Item;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function index() {
    	$items = Item::all();
    	foreach ($items as $key => $item) {
    		$item->view_item = [
    			'href' => 'api/v1/item/'. $item->id,
    			'method' => 'GET'
    		];
    	}

    	$response = [
    		'message' => 'List of all items',
    		'items' => $items
    	];

    	return response()->json($response, 200);
    }

    public function store(Request $request) {
    	$this->validate($request, [
    		'name' => 'required',
    		'key' => 'required'
    	]);

    	$name = $request->input('name');
    	$key = $request->input('key');

    	$item = new Item([
    		'name' => $name,
    		'key' => $key
    	]);

    	if($item->save()) {
    		$item->view_item = [
    			'href' => 'api/v1/item/'. $item->id,
    			'method' => 'GET'
    		];

    		$message = [
	    		'message' => 'Item created',
	    		'item' => $item
	    	];

	    	return response()->json($message, 200);
    	}

    	$response = [
    		'msg' => 'Error during creation'
    	];

    	return response()->json($response, 404);
    }

    public function show($id) {
    	$item = Item::where('id', $id)->firstOrFail();
    	$item->view_items = [
			'href' => 'api/v1/item',
			'method' => 'GET'
		];

		$message = [
    		'message' => 'Item information',
    		'item' => $item
    	];

    	return response()->json($message, 200);
    }

    public function update(Request $request, $id) {
    	$this->validate($request, [
    		'name' => 'required',
    		'key' => 'required'
    	]);

    	$name = $request->input('name');
    	$key = $request->input('key');

    	$item = Item::findOrFail($id);

    	if(!$item) {

    		$response = [
	    		'msg' => 'Item not found'
	    	];

	    	return response()->json($response, 401);
    	}

    	$item->name = $name;
    	$item->key = $key;

    	if(!$item->update()) {
    		$response = [
	    		'msg' => 'Error while updating'
	    	];

	    	return response()->json($response, 404);
    	}

    	$item->view_item = [
			'href' => 'api/v1/item/'. $item->id,
			'method' => 'GET'
		];

		$response = [
    		'message' => 'Item updated',
    		'item' => $item
    	];

    	return response()->json($response, 200);    	
    }

    public function destroy($id) {
    	$item = Item::findOrFail($id);

    	if(!$item->delete()) {
    		$response = [
	    		'msg' => 'Error while deleting'
	    	];

	    	return response()->json($response, 404);
    	}

    	$response = [
    		'msg' => 'Item deleted',
    		'create' => [
    			'href' => 'api/v1/item',
    			'method' => 'POST',
    			'params' => 'name, key'
    		]
    	];

    	return response()->json($response, 200);  
    }
}
