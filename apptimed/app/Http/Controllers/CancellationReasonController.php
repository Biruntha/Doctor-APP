<?php

namespace App\Http\Controllers;

use App\Models\CancellationReason;
use Illuminate\Http\Request;
use Validator;
use Session;
use Redirect;
use DB;
use Auth;

class CancellationReasonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:can-add-cancellationreason', ['only' => [
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

            $data = DB::table('cancellation_reasons')->select("*");

            if (!empty($searchFilter) and !is_null($searchFilter)) {
                $data = $data->where(function ($query) use($searchFilter) {
                    $query->where('name', 'LIKE', '%'.$searchFilter.'%')
                        ->orWhere('description', 'LIKE', '%'.$searchFilter.'%');
                });
            }
            $data = $data->paginate(20);
            return view('admin.cancellation-reasons.index', compact('data', 'searchFilter'));
        } catch(\Exception $e) {
            return redirect()->route('error');
        } catch(\Throwable $e) {
            return redirect()->route('error');
        }
    }

    public function create() {
        return view('admin.cancellation-reasons.create');
    }

    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required|unique:cancellation_reasons'
        );

        $customMessages = [
            'name.required' => 'Cancellation Reason Name cannot be empty',
            'name.unique' => 'This name has already been taken. Please try with another.'
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->route('cancellation-reasons.create')
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            try {
                $cancellationReason = new CancellationReason();
                $cancellationReason->name = $request->name;
                $cancellationReason->description = $request->description;
                $cancellationReason->save();

                Session::flash('message', 'CancellationReason has been added successfully');
            } catch(\Exception $e) {
                return redirect()->back()->with('error', 'Error while creating CancellationReason');
            } catch(\Throwable $e) {
                return redirect()->back()->with('error', 'Error while creating CancellationReason');
            }
            return redirect()->route('cancellation-reasons.index');
        }
    }

    public function edit(CancellationReason $cancellationReason)
    {
        return view('admin.cancellation-reasons.edit', compact('cancellationReason'));
    }

    public function update(Request $request, CancellationReason $cancellationReason)
    {
        $rules = array(
            'name' => 'required|unique:cancellation_reasons,name,' . $cancellationReason->id
        );

        $customMessages = [
            'name.required' => 'CancellationReason Name cannot be empty',
            'name.unique' => 'This name has already been taken. Please try with another.'
        ];
        
        $validator = Validator::make($request->all(), $rules, $customMessages);
        
        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->route('cancellation-reasons.edit', $cancellationReason->id)
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            try {
                $cancellationReason = CancellationReason::find($cancellationReason->id);
                $cancellationReason->name = $request->name;
                $cancellationReason->description = $request->description;
                $cancellationReason->save();

                Session::flash('message', 'CancellationReason has been updated successfully');
            } catch(\Exception $e) {
                return redirect()->back()->with('error', 'Error while updating CancellationReason');
            } catch(\Throwable $e) {
                return redirect()->back()->with('error', 'Error while updating CancellationReason');
            }
            return redirect()->route('cancellation-reasons.index');
        }
    }

    public function destroy(CancellationReason $cancellationReason)
    {
        try {
            $cancellationReason->delete();

            return redirect()->route('cancellation-reasons.index')
                        ->with('success','CancellationReason deleted successfully');
        } catch(\Exception $e) {
            return redirect()->back()->with('error', 'Error while deleting the CancellationReason');
        } catch(\Throwable $e) {
            return redirect()->back()->with('error', 'Error while deleting the CancellationReason');
        }
    }
}
