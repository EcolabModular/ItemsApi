<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Item;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    use ApiResponser;

    public $baseUri;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->baseUri = config('services.schedularies.base_uri');
    }

    /**
     * Returns a list of Item
     *
     * @return void
     */
    public function index()
    {
        $items = Item::all();
        return $this->showAll($items);
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
            'file' => 'required|mimes:jpg,png,jpeg,bmp',
            'laboratory_id' => 'required|integer|min:1'
        ];

        //dd($request);

        $this->validate($request,$rules);
        if($request->hasFile('file')){
            $original_filename = $request->file('file')->getClientOriginalName();
            $original_filename_arr = explode('.', $original_filename);
            $file_ext = end($original_filename_arr);
            $filename_encrypted = Str::random(25);
            $destination_path = './itemsphotos/';
            $photoItem = $filename_encrypted . "." . $file_ext;

            if ($request->file('file')->move($destination_path, $photoItem)) {
                $item = Item::create([
                    'name' => $request['name'],
                    'description' => $request['description'],
                    'imgItem' => url('/') . '/itemsphotos/' . $photoItem,
                    'encryptedImgName' => $filename_encrypted,
                    'extensionImg' => $file_ext,
                    'laboratory_id'=> $request['laboratory_id'],
                ]);

                $item->qrcode = "https://chart.googleapis.com/chart?cht=qr&chs=150&chl=".$item->id."&choe=UTF-8";

                $item->save();

                return $this->successResponse($item, Response::HTTP_CREATED);
            } else {
                return $this->errorResponse('Cannot upload photo', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        $item = Item::create($request->all());

        $item->qrcode = "https://chart.googleapis.com/chart?cht=qr&chs=150&chl=".$item->id."&choe=UTF-8";
        $item->imgItem = url('/') . '/itemsphotos/itemdefault.png';

        $item->save();

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
            'file' => 'mimes:jpg,png,jpeg,bmp',
            'laboratory_id' => 'integer|min:1'
        ];
        $this->validate($request,$rules);
        $item = Item::findOrFail($item);

        if($request->hasFile('file')){

            $original_filename = $request->file('file')->getClientOriginalName();
            $original_filename_arr = explode('.', $original_filename);
            $file_ext = end($original_filename_arr);
            $filename_encrypted = Str::random(25);
            $destination_path = './itemsphotos/';
            $photoItem = $filename_encrypted . "." . $file_ext;

            if ($request->file('file')->move($destination_path, $photoItem)) {

                /** ELIMINAR ARCHIVO */
                if($item->encryptedImgName != null && $item->extensionImg != null){ // SI EXISTE EL REGISTRO DE QUE UNA VEZ SE GUARDO UN ARCHIVO
                    if(file_exists($this->public_path('itemsphotos/' . $item->encryptedImgName . "." . $item->extensionImg))){ // COMPROBAMOS QUE EXISTA TAL ARCHIVO
                        unlink('./itemsphotos/' . $item->encryptedImgName . "." . $item->extensionImg);
                    }
                }

                $item->name = $request['name'];
                $item->description = $request['description'];
                $item->imgItem = url('/') . '/itemsphotos/' . $photoItem;
                $item->encryptedImgName = $filename_encrypted;
                $item->extensionImg = $file_ext;
                $item->laboratory_id = $request['laboratory_id'];

            } else {
                return $this->errorResponse('Cannot upload photo', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }else{
            $item->fill($request->all());
        }

        if($item->isClean()){
            return $this->errorResponse('At least one value must change',Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $item->qrcode = "https://chart.googleapis.com/chart?cht=qr&chs=150&chl=".$item->id."&choe=UTF-8";

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
        if($item->encryptedImgName != null && $item->extensionImg != null){ // SI EXISTE EL REGISTRO DE QUE UNA VEZ SE GUARDO UN ARCHIVO
            if(file_exists($this->public_path('itemsphotos/' . $item->encryptedImgName . "." . $item->extensionImg))){ // COMPROBAMOS QUE EXISTA TAL ARCHIVO
                unlink('./itemsphotos/' . $item->encryptedImgName . "." . $item->extensionImg);
            }
        }

        $item->delete();

        return $this->successResponse($item);
    }

    function public_path($path = '')
    {
        return env('PUBLIC_PATH', base_path('public')) . ($path ? '/' . $path : $path);
    }
}
