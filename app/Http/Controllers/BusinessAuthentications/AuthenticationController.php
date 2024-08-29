<?php

namespace App\Http\Controllers\BusinessAuthentications;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\BrandAuthentication;
use App\Models\CreatorsAuth;
use App\Models\CreatorsServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{

    /**
     * The function `brandRegistration` registers a brand by validating input data, hashing the
     * password, and storing brand information in the database.
     *
     * @param Request request The `brandRegistration` function is a PHP function that handles the
     * registration of a brand user. It takes a `Request` object as a parameter, which likely contains
     * the data submitted during the brand registration process.
     *
     * @return \Illuminate\Http\JsonResponse `brandRegistration` function returns a JSON response with the status, message, and
     * brand data. If the validation fails, it returns an error response with validation errors. If an
     * exception occurs during the process, it returns an error response with a message indicating that
     * something went wrong along with the exception message.
     */
    public function brandRegistration(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:brand_users,email',
                'brand_name' => 'required',
                'first_name' => 'required',
                'password' => 'required|min:8',
            ]);

            if ($validator->fails()) {
                return Helpers::ifValidatorFails($validator);
            }

            // Hash the password before storing it
            $hashedPassword = bcrypt($request->password);
            // Register the brand
            $brand = BrandAuthentication::create([
                'email' => $request->email,
                'brand_name' => $request->brand_name,
                'first_name' => $request->first_name,
                'password' => $hashedPassword,
                'last_name' => $request->last_name,
                'country' => $request->country,
                'location' => $request->location,
                'phone_number' => $request->phone_number,
                'status' => 1,
            ]);

            return response()->json(['status' => 1, 'message' => 'Brand registered successfully', 'brand_id' => $brand->id]);

        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * The function `creatorsRegistration` registers creators with validation and hashing of passwords
     * in PHP.
     *
     * @param Request request The `creatorsRegistration` function is a PHP function that handles the
     * registration of creators. It takes a `Request` object as a parameter, which likely contains data
     * submitted by a user through a form or an API request.
     *
     * @return \Illuminate\Http\JsonResponse function `creatorsRegistration` returns a JSON response with different data based on
     * the outcome of the registration process.
     */
    public function creatorsRegistration(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:creator_users,email',
                'first_name' => 'required',
                'last_name' => 'required',
                'phone_number' => 'required',
                'password' => 'required|min:8',
            ]);

            if ($validator->fails()) {
                return Helpers::ifValidatorFails($validator);
            }

            // Hash the password before storing it
            $hashedPassword = bcrypt($request->password);
            // Register the brand
            $creator = CreatorsAuth::create([
                'email' => $request->email,
                'first_name' => $request->first_name,
                'password' => $hashedPassword,
                'last_name' => $request->last_name,
                'country' => $request->country,
                'location' => $request->location,
                'phone_number' => $request->phone_number,
                'status' => 1,
            ]);

            return response()->json(['status' => 1, 'message' => 'creator registered successfully', 'creator_id' => $creator->id]);

        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * The function `creatorsLogin` attempts to authenticate a user with email and password, returning
     * a JSON response based on the authentication result.
     *
     * @param Request request The `creatorsLogin` function is a controller method that handles the
     * login process for creators. It takes a `Request` object as a parameter, which contains the data
     * submitted by the user trying to log in.
     *
     * @return \Illuminate\Http\JsonResponse function `creatorsLogin` returns a JSON response with different data based on the
     * outcome of the login attempt. Here are the possible return scenarios:
     */
    public function creatorsLogin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|min:8',
            ]);

            if ($validator->fails()) {
                return Helpers::ifValidatorFails($validator);
            }

            // Attempt to log in the user
            $credentials = $request->only('email', 'password');

            if (Auth::guard('creator')->attempt($credentials)) {
                // Get the authenticated user
                $user = Auth::guard('creator')->user();

                return response()->json([
                    'status' => 1,
                    'message' => 'Login successful',
                    'user' => $user,
                ]);
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => 'Invalid email or password',
                ]);
            }

        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * The function `BrandLogin` in PHP validates user credentials and attempts to log in a user with a
     * brand guard, returning appropriate JSON responses based on the outcome.
     *
     * @param Request request The `BrandLogin` function you provided is a PHP function that handles the
     * login process for a brand user. It validates the email and password provided in the request,
     * attempts to log in the user using the `Auth` facade with the `brand` guard, and returns a JSON
     * response based on the
     *
     * @return \Illuminate\Http\JsonResponse `BrandLogin` function returns a JSON response based on the validation and
     * authentication process. Here are the possible return scenarios:
     */
    public function brandLogin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|min:8',
            ]);

            if ($validator->fails()) {
                return Helpers::ifValidatorFails($validator);
            }

            // Attempt to log in the user
            $credentials = $request->only('email', 'password');

            if (Auth::guard('brand')->attempt($credentials)) {
                // Get the authenticated user
                $user = Auth::guard('brand')->user();

                return response()->json([
                    'status' => 1,
                    'message' => 'Login successful',
                    'user' => $user,
                ]);
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => 'Invalid email or password',
                ]);
            }

        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * The function `forgotPassword` in PHP validates input, determines the broker based on user type,
     * and sends a password reset link accordingly.
     *
     * @param Request request The `forgotPassword` function is responsible for handling the forgot
     * password functionality. Here's a breakdown of the code:
     *
     * @return \Illuminate\Http\JsonResponse `forgotPassword` function returns a JSON response with a status code and a message
     * based on the outcome of the password reset link sending process. If the password reset link is
     * successfully sent, it returns a status of 1 and a message indicating that the password reset link
     * has been sent. If there is an issue with sending the password reset link, it returns a status of
     * 0 and a message
     */
    public function forgotPassword(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'userType' => 'required',
            ]);

            if ($validator->fails()) {
                return Helpers::ifValidatorFails($validator);
            }

            // Determine the broker based on the user type
            $broker = $request->userType === 'creator' ? 'creator_users' : 'brand_users';

            // Send the password reset link
            $status = Password::broker($broker)->sendResetLink(
                ['email' => $request->email],
            );

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'status' => 1,
                    'message' => 'Password reset link sent',
                ]);
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => 'Unable to send password reset link',
                ]);
            }
        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeCreatorService(Request $request)
    {
        try {
            $validated = $request->validate([
                'creator_id' => 'required|integer',
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'discount' => 'nullable|numeric',
                'status' => 'required|boolean',
            ]);

            $service = CreatorsServices::create($validated);

            return response()->json(['status' => true, 'message' => 'Service created successfully', 'data' => $service]);
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
    public function getCreatorServiceById($id)
    {
        try {
            $service = CreatorsServices::findOrFail($id);

            return response()->json(['status' => true, 'data' => $service]);
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
    public function updateCreatorService(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'creator_id' => 'required|integer',
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'discount' => 'nullable|numeric',
                'status' => 'required|boolean',
            ]);

            $service = CreatorsServices::findOrFail($id);
            $service->update($validated);

            return response()->json(['status' => true, 'message' => 'Service updated successfully', 'data' => $service]);
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
    public function deleteCreatorService($id)
    {
        try {
            $service = CreatorsServices::findOrFail($id);
            $service->delete();

            return response()->json(['status' => true, 'message' => 'Service deleted successfully']);
        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * Get a list of all services.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCreatorsServices()
    {
        try {
            $services = CreatorsServices::all();

            return response()->json(['status' => true, 'data' => $services]);
        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }
}
