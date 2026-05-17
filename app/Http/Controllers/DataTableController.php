<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DataTableController extends Controller
{
        //
        public function clientside(Request $request){
            $data = User::get();
            $data1 = Auth::user();
                return view('datatable.clientside',compact('data','request','data1'));
        }
    
    
}
