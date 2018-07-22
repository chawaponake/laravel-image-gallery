<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $overview = DB::table('medias')
                            ->selectRaw(' SUM(size) as total_sizes')
                            ->selectRaw(' COUNT(*) as total_files')
                            ->get();

            $composition = DB::table('medias')
                            ->select('mime_type')
                            ->selectRaw(' COUNT(*) as total_files')
                            ->selectRaw(' SUM(size) as total_sizes')
                            ->groupBy('mime_type')
                            ->get();
            return response()->json([
                'overview' => $overview,
                'composition' => $composition
            ]);
        }
        return view('layouts.app');
    }
}
