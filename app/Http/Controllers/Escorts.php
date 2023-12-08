<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class Escorts extends Controller
{
    public function kinks(Request $request)
    {
        try {
            // $user = $request->user();
            // $my_plan = $user->plan;
            $query = User::where('is_escort', true)
                ->inRandomOrder()
                ->when($request->has('tags'), function ($query) use ($request) {
                    $query->whereJsonContains('tags', $request->tags);
                })
                ->when($request->has('plan'), function ($query) use ($request) {
                    // if($request->has('min-plan'))  {
                    //     // get list of 
                    // }
                })
                ->when($request->has('fees'), function ($query) use ($request) {
                    $fee = explode('-', $request->fees);
                    $query->whereBetween('amount', [$fee[0], $fee[1]]);
                })
                ->when($request->has('location'), function ($query) use ($request) {
                    $query->where('location', $request->location);
                })
                ->paginate(24);


            return get_success_response($query);
        } catch (\Throwable $th) {
            return get_error_response(['error' =>  $th->getMessage()]);
        }
    }

    public function kink(Request $request, $id)
    {
        try {
            $kink = User::with('messages', 'orders')->find($id);
            return get_success_response($kink);
        } catch (\Throwable $th) {
            return get_error_response(['error' =>  $th->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            if ((int)auth()->id() !== (int)$id) {
                return get_error_response(['error' =>  'You can only delete your own account']);
            }
            $record =  User::whereId($id)->first();
            if ($record->delete())
                return get_success_response(['message' => 'Record deleted successfully']);
            return get_error_response(['error' =>  'Unable to delete, please contact support']);
        } catch (\Throwable $th) {
            return get_error_response(['error' =>  $th->getMessage()]);
        }
    }
}
