<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Item;
use Illuminate\Http\Response;

class ItemController extends Controller
{
    use ApiResponser;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Returns a list of Item
     *
     * @return void
     */
    public function index()
    {
        $items = Item::all();
        return $this->successResponse($items);
    }
    /**
     * Creates an instance of Item
     *
     * @return void
     */
    public function store(Request $request)
    {
        $rules =[
            'name' => 'required|max:50',
            'description' => 'required|max:1000',
            'laboratory_id' => 'required|integer|min:1'
        ];
        $this->validate($request,$rules);

        $item = Item::create($request->all());

        return $this->successResponse($item,Response::HTTP_CREATED);
    }
    /**
     * Returns an specific Item
     *
     * @return void
     */
    public function show($item)
    {
        $item = Item::findOrFail($item);

        return $this->successResponse($item);
    }
    /**
     * Updates an specific Item
     *
     * @return void
     */
    public function update(Request $request,$item)
    {
        $rules =[
            'name' => 'max:50',
            'description' => 'max:1000',
            'laboratory_id' => 'required|integer|min:1'
        ];
        $this->validate($request,$rules);

        $item = Item::findOrFail($item);

        $item->fill($request->all());

        if($item->isClean()){
            return $this->errorResponse('At least one value must change',Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $item->save();

        return $this->successResponse($item);
    }
    /**
     * Returns an specific Item
     *
     * @return void
     */
    public function destroy($item)
    {
        $item = Item::findOrFail($item);

        $item->delete();

        return $this->successResponse($item);
    }
    //
}
