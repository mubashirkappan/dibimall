<?php

namespace App\Http\Controllers;

use App\Actions\Items\DeleteItemAction;
use App\Actions\Items\IndexItemsAction;
use App\Actions\Items\SaveItemAction;
use App\Actions\Items\UpdateItemAction;
use App\Http\Requests\CreateItemRequest;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ItemsController extends Controller
{
    public function index(Request $request, IndexItemsAction $action)
    {
        $response = $action->execute($request->shop_id);
        if ($response['success']) {
            return ItemResource::collection($response['data'])->additional(['message' => $response['message'], 'success' => $response['success']]);
        } else {
            return response()->json(['message' => $response['message'], 'success' => $response['success']], 500);
        }
    }

    public function create(CreateItemRequest $request, SaveItemAction $action)
    {
        $response = $action->execute($request);
        if ($response['success']) {
            return (new ItemResource($response['data']))
                ->additional(['message' => $response['message'], 'success' => $response['success']]);
        } else {
            return response()->json(['message' => $response['message'], 'success' => $response['success']], 500);
        }
    }

    public function delete($encrypted_id, DeleteItemAction $action)
    {
        $id = Crypt::decrypt($encrypted_id);
        $response = $action->execute($id);

        if ($response['success']) {
            return response()->json(['message' => $response['message'], 'success' => $response['success']]);
        } else {
            return response()->json(['message' => $response['message'], 'success' => $response['success']], 500);
        }
    }

    public function update(CreateItemRequest $request, UpdateItemAction $action)
    {
        $response = $action->execute($request, $request->id);
        if ($response['success']) {
            return (new ItemResource($response['data']))
                ->additional(['message' => $response['message'], 'success' => $response['success']]);
        } else {
            return response()->json(['message' => $response['message'], 'success' => $response['success']], 500);
        }
    }
    public function statusChange($encryptedId){
        $item = Item::find(decrypt($encryptedId));
        $item->update(['active'=>!$item->active]);
        return response()->json([
            'message'=>'status changed successfully'
        ]);
    }
}
