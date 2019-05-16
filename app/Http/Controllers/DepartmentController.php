<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use App\Http\Resources\DepartmentResource;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return DepartmentResource::collection(Department::all());
        } catch (\Exception $e) {
            return response()->json($e->getCode(), 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $department = new Department;
        $department->name = $request->name;
        try{
            $department->save();
            return response()->json($department, 201);
        } catch(\Exception $e) {
            return response()->json($e->getCode(), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        try {
            return new DepartmentResource($department);
        } catch (\Exception $e) {
            return response()->json($e->getCode(), 400);
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        try{
            $department->name = $request->name;
            $department->save();
            return response()->json($department, 200);
        } catch(\Exception $e) {
            return response()->json($e->getCode(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        try {
            $department->delete();
            return response()->json($department, 200);
        } catch(\Exception $e) {
            return response()->json($e->getCode(), 400);
        }
        
    }
}
