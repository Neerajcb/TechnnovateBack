<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class leadController extends Controller
{
    public function index()
    {
        // You mentioned returning a view, but for API typically you return JSON responses
        return response()->json(['message' => 'Welcome to Lead API']);
    }

    public function leadpost(Request $request)
    {
        // Validate the request data
        $validation = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'service' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string'],
        ]);

        // If validation fails, return the validation errors
        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        // Begin a database transaction
        DB::beginTransaction();
        try {
            // Create a new Lead instance and save it to the database
            $lead = Lead::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'service' => $request->service,
                'message' => $request->message,
            ]);

            // Commit the transaction
            DB::commit();

            // Return a JSON response with the created lead data
            return response()->json($lead, 201);
        } catch (\Exception $e) {
            // Rollback the transaction if an error occurs
            DB::rollback();

            // Log the error for debugging purposes
            \Log::error($e);

            // Return an error response
            return response()->json(['error' => 'An error occurred while creating the lead.'], 500);
        }
    }

    public function getData(Request $request)
    {
        // Validate the request data
        $validation = Validator::make($request->all(), [
            'id' => ['required', 'exists:leads,id'],
        ]);

        // If validation fails, return the validation errors
        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        // Retrieve the id from the request
        $id = $request->input('id');

        // Query the Lead model to fetch data based on the id
        $data = Lead::select('name', 'phone', 'email', 'service', 'message')->find($id);

        // Return the fetched data
        return response()->json($data);
    }
}
