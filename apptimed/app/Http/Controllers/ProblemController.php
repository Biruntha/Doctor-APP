<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use App\Models\ProblemType;
use Illuminate\Http\Request;
use Validator;
use Session;
use Redirect;
use DB;
use Auth;

class ProblemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:can-add-problem', ['only' => [
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
            $problemTypeFilter = $request->problem_type;

            $data = DB::table('problems as p')->select('p.id', "p.name", 'p.description', 'pt.name as problem_type')
                    ->leftjoin("problem_types as pt", 'p.problem_type', '=', 'pt.id');

            if (!empty($searchFilter) and !is_null($searchFilter)) {
                $data = $data->where(function ($query) use($searchFilter) {
                    $query->where('p.name', 'LIKE', '%'.$searchFilter.'%')
                        ->orWhere('p.description', 'LIKE', '%'.$searchFilter.'%');
                });
            }

            if(isset($problemTypeFilter) && $problemTypeFilter != "") {
                $data->where('p.problem_type', '=', $problemTypeFilter);
            }

            $data = $data->paginate(20);

            $problemTypes = ProblemType::all();
            return view('admin.problems.index', compact('data', 'searchFilter', 'problemTypes', 'problemTypeFilter'));
        } catch(\Exception $e) {
            return redirect()->route('error');
        } catch(\Throwable $e) {
            return redirect()->route('error');
        }
    }

    public function create() {
        $problemTypes = ProblemType::all();
        return view('admin.problems.create', compact('problemTypes'));
    }

    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required|unique:problems',
            'problem_type' => 'required'
        );

        $customMessages = [
            'name.required' => 'Problem Name cannot be empty',
            'problem_type.required' => 'Problem Type need to be selected',
            'name.unique' => 'This name has already been taken. Please try with another.'
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->route('problems.create')
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            try {
                $problem = new Problem();
                $problem->name = $request->name;
                $problem->problem_type = $request->problem_type;
                $problem->description = $request->description;
                $problem->save();

                Session::flash('message', 'Problem has been added successfully');
            } catch(\Exception $e) {
                return redirect()->back()->with('error', 'Error while creating Problem');
            } catch(\Throwable $e) {
                return redirect()->back()->with('error', 'Error while creating Problem');
            }
            return redirect()->route('problems.index');
        }
    }

    public function edit(Problem $problem)
    {
        $problemTypes = ProblemType::all();
        return view('admin.problems.edit', compact('problemTypes', 'problem'));
    }

    public function update(Request $request, Problem $problem)
    {
        $rules = array(
            'name' => 'required|unique:problems,name,' . $problem->id,
            'problem_type' => 'required'
        );

        $customMessages = [
            'name.required' => 'Problem Name cannot be empty',
            'problem_type.required' => 'Problem Type need to be selected',
            'name.unique' => 'This name has already been taken. Please try with another.'
        ];
        
        $validator = Validator::make($request->all(), $rules, $customMessages);
        
        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->route('problems.edit', $problem->id)
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            try {
                $problem = Problem::find($problem->id);
                $problem->name = $request->name;
                $problem->problem_type = $request->problem_type;
                $problem->description = $request->description;
                $problem->save();

                Session::flash('message', 'Problem has been updated successfully');
            } catch(\Exception $e) {
                return redirect()->back()->with('error', 'Error while updating Problem');
            } catch(\Throwable $e) {
                return redirect()->back()->with('error', 'Error while updating Problem');
            }
            return redirect()->route('problems.index');
        }
    }

    public function destroy(Problem $problem)
    {
        try {
            $problem->delete();

            return redirect()->route('problems.index')
                        ->with('success','Problem deleted successfully');
        } catch(\Exception $e) {
            return redirect()->back()->with('error', 'Error while deleting the Problem');
        } catch(\Throwable $e) {
            return redirect()->back()->with('error', 'Error while deleting the Problem');
        }
    }
}
