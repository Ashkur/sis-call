<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Department;
use Illuminate\Http\Request;
use App\Http\Resources\EmployeeResource;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return EmployeeResource::collection(Employee::all());
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
        try{
            $employee = new Employee;
            $employee->name = $request->name;
            $employee->login = $request->login;
            $employee->password = Hash::make('secret');

            $department = Department::find($request->department);
            $department->employees()->save($employee);

            return response()->json(new EmployeeResource($employee), 201);
        } catch(\Exception $e) {
            return response()->json($e->getCode(), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        try {
            return new EmployeeResource($employee);
        } catch (\Exception $e) {
            return response()->json($e->getCode(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        try{
            $employee->name = $request->name;
            $employee->login = $request->login;

            $department = Department::find($request->department);
            $department->employees()->save($employee);

            return response()->json(new EmployeeResource($employee), 200);
        } catch(\Exception $e) {
            return response()->json($e->getCode(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        try {
            $employee->delete();
            return response()->json($employee, 200);
        } catch(\Exception $e) {
            return response()->json($e->getCode(), 400);
        }
    }
}
