<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdvertisementListRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\Advertisement\AdvertisementResource;
use App\Models\Advertisement;
use App\Models\AdvertisementImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class AdvertisementController extends Controller
{
    /**
     * @param AdvertisementListRequest $request
     * @return AnonymousResourceCollection
     */
    public function list(AdvertisementListRequest $request): AnonymousResourceCollection
    {
        $sort = $request->sort ?? 'id';
        $sortOrder = $request->sort_order ?? 'desc';
        $data = Advertisement::orderBy($sort, $sortOrder)->paginate(5);
        return AdvertisementResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request): JsonResponse
    {
        $advertisement = new Advertisement([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::user()->getKey()
        ]);
        $advertisement->save();

        $images = [];
        if($request->hasfile('files')) {
            foreach($request->file('files') as $image) {
                $filename = time().$image->getClientOriginalName();
                $image->move(public_path('uploads/profile_images'), $filename);

                $advertisementImage = new AdvertisementImage;
                $advertisementImage->path = $filename;
                $images[] = $advertisementImage;
            }
        }

        $advertisement->images()->saveMany($images);

        return response()->json([
            'success' => true,
            'message' => "The advertisement successfully added"
        ], 201);
    }

    /**
     * @param int $id
     * @return AdvertisementResource
     */
    public function show(int $id): AdvertisementResource
    {
        $advertisement = Advertisement::findOrFail($id);
        return new AdvertisementResource($advertisement->load('images'));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        $advertisement = Advertisement::findOrFail($id);
        return response()->json($advertisement);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UserRequest $request, int $id): JsonResponse
    {
        $advertisement = Advertisement::findOrFail($id);
        $advertisement->update($request->all());

        return response()->json([
            'success' => true,
            'message' => "The advertisement successfully updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $advertisement = Advertisement::findOrFail($id);
        $advertisement->delete();

        return response()->json([
            'success' => true,
            'message' => "The advertisement successfully deleted"
        ], 204);
    }
}
