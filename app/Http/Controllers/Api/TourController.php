<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TourRequest;
use App\Http\Requests\ToursListRequest;
use App\Http\Resources\TourResource;
use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TourController extends Controller
{
    public function index(Travel $travel, ToursListRequest $request)
    {

        $tours = $travel->tours()
        ->when($request->price_from, function($query) use($request){
            $query->where('price', '>=', $request->price_from * 100);
        })
        ->when($request->price_to, function($query) use($request){
            $query->where('price', '<=', $request->price_to * 100);
        })
        ->when($request->date_from, function($query) use($request){
            $query->where('starting_date', '>=', $request->date_from);
        })
        ->when($request->date_to, function($query) use($request){
            $query->where('starting_date', '<=', $request->date_to);
        })
        ->when($request->sort_by && $request->sort_order, function($query) use($request){
            $query->orderBy($request->sort_by, $request->sort_order);
        })
        ->orderBy('starting_date')
        ->paginate();
        return TourResource::collection($tours);
    }

    public function store(Travel $travel, TourRequest $request)
    {
        $tour = $travel->tours()->create($request->validated());
        return new TourResource($tour);
    }
}
