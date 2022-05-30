<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdvertisementListRequest;
use App\Http\Requests\AdvertisementRequest;
use App\Models\Advertisement;
use App\Models\AdvertisementImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AdvertisementController extends Controller
{
    /**
     * @param AdvertisementListRequest $request
     * @return JsonResponse
     */
    public function list(AdvertisementListRequest $request): JsonResponse
    {
        $sort = $request->sort ?? 'id';
        $sortOrder = $request->sort_order ?? 'desc';
        $data = Advertisement::orderBy($sort, $sortOrder)->paginate(5);
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdvertisementRequest $request
     * @return JsonResponse
     */
    public function store(AdvertisementRequest $request): JsonResponse
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
        ]);
    }

    /**
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $advertisement = Advertisement::find($id);
        return response()->json($advertisement->load('images'));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        $advertisement = Advertisement::find($id);
        return response()->json($advertisement);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdvertisementRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(AdvertisementRequest $request, int $id): JsonResponse
    {
        $advertisement = Advertisement::find($id);
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
        $advertisement = Advertisement::find($id);
        $advertisement->delete();

        return response()->json([
            'success' => true,
            'message' => "The advertisement successfully deleted"
        ]);
    }
}
