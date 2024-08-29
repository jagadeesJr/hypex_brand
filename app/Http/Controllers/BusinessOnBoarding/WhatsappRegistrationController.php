<?php

namespace App\Http\Controllers\BusinessOnBoarding;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\WhatsappRegistration;
use Exception;
use Illuminate\Http\Request;

class WhatsappRegistrationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeWhatsappReg(Request $request)
    {
        try {
            $validated = $request->validate([
                'business_id' => 'required|integer',
                'status' => 'required|boolean',
            ]);

            $whatsappReg = WhatsappRegistration::create($validated);

            return response()->json(['status' => true, 'message' => 'Record created successfully', 'data' => $whatsappReg]);
        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWhatsappRegById($id)
    {
        try {
            $whatsappReg = WhatsappRegistration::findOrFail($id);

            return response()->json(['status' => true, 'data' => $whatsappReg]);
        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateWhatsappReg(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'business_id' => 'required|integer',
                'status' => 'required|boolean',
            ]);

            $whatsappReg = WhatsappRegistration::findOrFail($id);
            $whatsappReg->update($validated);

            return response()->json(['status' => true, 'message' => 'Record updated successfully', 'data' => $whatsappReg]);
        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteWhatsappReg($id)
    {
        try {
            $whatsappReg = WhatsappRegistration::findOrFail($id);
            $whatsappReg->delete();

            return response()->json(['status' => true, 'message' => 'Record deleted successfully']);
        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * Get a list of all records.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllWhatsappReg()
    {
        try {
            $whatsappRegs = WhatsappRegistration::all();

            return response()->json(['status' => true, 'data' => $whatsappRegs]);
        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }
}
