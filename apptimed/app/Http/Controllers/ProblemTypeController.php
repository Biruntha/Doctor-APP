<?php

namespace App\Http\Controllers;

use App\Models\ProblemType;
use Illuminate\Http\Request;
use Validator;
use Session;
use Redirect;
use DB;
use Auth;

class ProblemTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:can-add-problemtype', ['only' => [
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

            $data = DB::table('problem_types')->select("*");

            if (!empty($searchFilter) and !is_null($searchFilter)) {
                $data = $data->where(function ($query) use($searchFilter) {
                    $query->where('name', 'LIKE', '%'.$searchFilter.'%')
                        ->orWhere('description', 'LIKE', '%'.$searchFilter.'%');
                });
            }
            $data = $data->paginate(20);
            return view('admin.problem-types.index', compact('data', 'searchFilter'));
        } catch(\Exception $e) {
            return redirect()->route('error');
        } catch(\Throwable $e) {
            return redirect()->route('error');
        }
    }

    public function create() {
        return view('admin.problem-types.create');
    }

    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required|unique:problem_types'
        );

        $customMessages = [
            'name.required' => 'Problem Type Name cannot be empty',
            'name.unique' => 'This name has already been taken. Please try with another.'
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->route('problem-types.create')
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            try {
                $problemType = new ProblemType();
                $problemType->name = $request->name;
                $problemType->description = $request->description;
                $problemType->save();

                Session::flash('message', 'ProblemType has been added successfully');
            } catch(\Exception $e) {
                return redirect()->back()->with('error', 'Error while creating ProblemType');
            } catch(\Throwable $e) {
                return redirect()->back()->with('error', 'Error while creating ProblemType');
            }
            return redirect()->route('problem-types.index');
        }
    }

    public function edit(ProblemType $problemType)
    {
        return view('admin.problem-types.edit', compact('problemType'));
    }

    public function update(Request $request, ProblemType $problemType)
    {
        $rules = array(
            'name' => 'required|unique:problem_types,name,' . $problemType->id
        );

        $customMessages = [
            'name.required' => 'ProblemType Name cannot be empty',
            'name.unique' => 'This name has already been taken. Please try with another.'
        ];
        
        $validator = Validator::make($request->all(), $rules, $customMessages);
        
        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->route('problem-types.edit', $problemType->id)
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            try {
                $problemType = ProblemType::find($problemType->id);
                $problemType->name = $request->name;
                $problemType->description = $request->description;
                $problemType->save();

                Session::flash('message', 'ProblemType has been updated successfully');
            } catch(\Exception $e) {
                return redirect()->back()->with('error', 'Error while updating ProblemType');
            } catch(\Throwable $e) {
                return redirect()->back()->with('error', 'Error while updating ProblemType');
            }
            return redirect()->route('problem-types.index');
        }
    }

    public function destroy(ProblemType $problemType)
    {
        try {
            $problemType->delete();

            return redirect()->route('problem-types.index')
                        ->with('success','ProblemType deleted successfully');
        } catch(\Exception $e) {
            return redirect()->back()->with('error', 'Error while deleting the ProblemType');
        } catch(\Throwable $e) {
            return redirect()->back()->with('error', 'Error while deleting the ProblemType');
        }
    }
}
