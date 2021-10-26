<?php

namespace App\Http\Controllers;

use App\Models\Credential;
use Illuminate\Http\Request;

class CredentialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->isAdmin()) {
            $credentials = Credential::all();
        } else {
            $credentials = auth()->user()->credentials()->get();
        }
        
        return view('credentials.index')->with(compact('credentials'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('credentials.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $credential = auth()->user()->credentials()->create([
            'description' => $request->get('description')
        ]);
        
        if ($request->has('media')) {
            foreach ($request->get('media') as $file) {
                $credential->addMediaFromDisk('tmp/filepond/' . $file)
                        ->toMediaCollection('files');
            }
        }

        return redirect('credentials')->withSuccess('Credential successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($credential = Credential::findOrFail($id))
            return view('credentials.show')->with(compact('credential'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('credentials.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $credential = Credential::findOrFail($id);
        $credential->delete(); 
        return redirect('credentials')->withSuccess('Credential removed successfully');
    }
}
