<?php

namespace App\Http\Controllers;

use App\Models\Specialization;
use Illuminate\Http\Request;
use Validator;
use Session;
use Redirect;
use DB;
use Auth;
use Image;
use Storage;

class SpecializationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:can-add-specialization', ['only' => [
            'index',
            'create',
            'store',
            'edit',
            'update',
            'destroy'
        ]]);
    }

    public function index(Request $request) {
        try {
            $searchFilter = $request->searchFilter;

            $data = DB::table('specializations')->select("*");

            if (!empty($searchFilter) and !is_null($searchFilter)) {
                $data = $data->where(function ($query) use($searchFilter) {
                    $query->where('name', 'LIKE', '%'.$searchFilter.'%')
                        ->orWhere('description', 'LIKE', '%'.$searchFilter.'%');
                });
            }
            $data = $data->paginate(20);
            return view('admin.specializations.index', compact('data', 'searchFilter'));
        } catch(\Exception $e) {
            return redirect()->route('error');
        } catch(\Throwable $e) {
            return redirect()->route('error');
        }
    }

    public function create() {
        return view('admin.specializations.create');
    }

    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required|unique:specializations'
        );

        $customMessages = [
            'name.required' => 'Specialization Name cannot be empty',
            'name.unique' => 'This name has already been taken. Please try with another.'
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->route('specializations.create')
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            try {
                $specialization = new Specialization();
                $specialization->name = $request->name;
                $specialization->description = $request->description;
                if ($request->hasFile('logo')) {
                    $image      = $request->file('logo');
                    $fileName   = time() . '.' . $image->getClientOriginalExtension();
                    

                    $img = Image::make($image->getRealPath());
                    $img->resize(120, 120, function ($constraint) {
                        $constraint->aspectRatio();                 
                    });
                    $img->stream();
        
                    Storage::disk('local')->put('/Specialization/LogoImages'.'/'.$fileName, $img);
                    $specialization->logo = $fileName;
                }
                $specialization->save();

                Session::flash('message', 'Specialization has been added successfully');
            } catch(\Exception $e) {
                return redirect()->back()->with('error', 'Error while creating Specialization');
            } catch(\Throwable $e) {
                return redirect()->back()->with('error', 'Error while creating Specialization');
            }
            return redirect()->route('specializations.index');
        }
    }

    public function edit(Specialization $specialization)
    {
        return view('admin.specializations.edit', compact('specialization'));
    }

    public function update(Request $request, Specialization $specialization)
    {
        $rules = array(
            'name' => 'required|unique:specializations,name,' . $specialization->id
        );

        $customMessages = [
            'name.required' => 'Specialization Name cannot be empty',
            'name.unique' => 'This name has already been taken. Please try with another.'
        ];
        
        $validator = Validator::make($request->all(), $rules, $customMessages);
        
        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->route('specializations.edit', $specialization->id)
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            try {
                $specialization = Specialization::find($specialization->id);
                $specialization->name = $request->name;
                $specialization->description = $request->description;
                if ($request->hasFile('logo')) {
                    $image      = $request->file('logo');
                    $fileName   = time() . '.' . $image->getClientOriginalExtension();
                    

                    $img = Image::make($image->getRealPath());
                    $img->resize(120, 120, function ($constraint) {
                        $constraint->aspectRatio();                 
                    });
                    $img->stream();
        
                    Storage::disk('local')->put('/Specialization/LogoImages'.'/'.$fileName, $img);
                    $specialization->logo = $fileName;
                }
                $specialization->save();

                Session::flash('message', 'Specialization has been updated successfully');
            } catch(\Exception $e) {
                return redirect()->back()->with('error', 'Error while updating Specialization');
            } catch(\Throwable $e) {
                return redirect()->back()->with('error', 'Error while updating Specialization');
            }
            return redirect()->route('specializations.index');
        }
    }

    public function destroy(Specialization $specialization)
    {
        try {
            $specialization->delete();

            return redirect()->route('specializations.index')
                        ->with('success','Specialization deleted successfully');
        } catch(\Exception $e) {
            return redirect()->back()->with('error', 'Error while deleting the Specialization');
        } catch(\Throwable $e) {
            return redirect()->back()->with('error', 'Error while deleting the Specialization');
        }
    }
}
