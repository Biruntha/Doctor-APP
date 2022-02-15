<?php

namespace App\Http\Controllers;

use App\Models\TransferReason;
use Illuminate\Http\Request;
use Validator;
use Session;
use Redirect;
use DB;
use Auth;

class TransferReasonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:can-add-transferreason', ['only' => [
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

            $data = DB::table('transfer_reasons')->select("*");

            if (!empty($searchFilter) and !is_null($searchFilter)) {
                $data = $data->where(function ($query) use($searchFilter) {
                    $query->where('name', 'LIKE', '%'.$searchFilter.'%')
                        ->orWhere('description', 'LIKE', '%'.$searchFilter.'%');
                });
            }
            $data = $data->paginate(20);
            return view('admin.transfer-reasons.index', compact('data', 'searchFilter'));
        } catch(\Exception $e) {
            return redirect()->route('error');
        } catch(\Throwable $e) {
            return redirect()->route('error');
        }
    }

    public function create() {
        return view('admin.transfer-reasons.create');
    }

    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required|unique:transfer_reasons'
        );

        $customMessages = [
            'name.required' => 'Transfer Reason Name cannot be empty',
            'name.unique' => 'This name has already been taken. Please try with another.'
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->route('transfer-reasons.create')
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            try {
                $transferReason = new TransferReason();
                $transferReason->name = $request->name;
                $transferReason->description = $request->description;
                $transferReason->save();

                Session::flash('message', 'TransferReason has been added successfully');
            } catch(\Exception $e) {
                return redirect()->back()->with('error', 'Error while creating TransferReason');
            } catch(\Throwable $e) {
                return redirect()->back()->with('error', 'Error while creating TransferReason');
            }
            return redirect()->route('transfer-reasons.index');
        }
    }

    public function edit(TransferReason $transferReason)
    {
        return view('admin.transfer-reasons.edit', compact('transferReason'));
    }

    public function update(Request $request, TransferReason $transferReason)
    {
        $rules = array(
            'name' => 'required|unique:transfer_reasons,name,' . $transferReason->id
        );

        $customMessages = [
            'name.required' => 'RransferReason Name cannot be empty',
            'name.unique' => 'This name has already been taken. Please try with another.'
        ];
        
        $validator = Validator::make($request->all(), $rules, $customMessages);
        
        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->route('transfer-reasons.edit', $transferReason->id)
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            try {
                $transferReason = TransferReason::find($transferReason->id);
                $transferReason->name = $request->name;
                $transferReason->description = $request->description;
                $transferReason->save();

                Session::flash('message', 'TransferReason has been updated successfully');
            } catch(\Exception $e) {
                return redirect()->back()->with('error', 'Error while updating TransferReason');
            } catch(\Throwable $e) {
                return redirect()->back()->with('error', 'Error while updating TransferReason');
            }
            return redirect()->route('transfer-reasons.index');
        }
    }

    public function destroy(TransferReason $transferReason)
    {
        try {
            $transferReason->delete();

            return redirect()->route('transfer-reasons.index')
                        ->with('success','TransferReason deleted successfully');
        } catch(\Exception $e) {
            return redirect()->back()->with('error', 'Error while deleting the TransferReason');
        } catch(\Throwable $e) {
            return redirect()->back()->with('error', 'Error while deleting the TransferReason');
        }
    }
}
