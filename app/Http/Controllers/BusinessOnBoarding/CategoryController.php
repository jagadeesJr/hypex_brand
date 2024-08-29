<?php

namespace App\Http\Controllers\BusinessOnBoarding;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    /**
     * The function `createCategory` in PHP validates and creates a new category, handling errors and
     * duplicate entries.
     *
     * @param Request request The `createCategory` function you provided is a PHP function that
     * creates a new category based on the data received in the request. It performs validation to
     * ensure that the required fields are present and within the specified limits. It also checks if
     * a category with the same name already exists before creating a new one
     *
     * @return \Illuminate\Http\JsonResponse `createCategory` function returns a JSON response based on the outcome of creating
     * a new category.
     */
    public function createCategory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return Helpers::ifValidatorFails($validator);
            }

            $CategoryExist = Category::where('name', $request->name)->first();
            if ($CategoryExist) {
                return Helpers::existResponse('BrandBusiness name');

            }

            $Category = Category::create($request->all());
            if ($Category) {
                return Helpers::createResponse('Category created', $Category);
            } else {
                return Helpers::failResponse('Category created');
            }

        } catch (\Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * The function `readCategory` retrieves all categories and returns a JSON response with the
     * categories if found, or an error message if retrieval fails.
     *
     * @return \Illuminate\Http\JsonResponse `readCategory` function returns a JSON response. If categories are found, it
     * returns a JSON response with status true, a success message, and the list of categories. If no
     * categories are found, it returns a JSON response with status false and a message indicating
     * that no categories were found. If an exception occurs during the process, it returns a JSON
     * response with an error message indicating the failure to
     */
    public function readCategory()
    {
        try {
            $Category = Category::all();
            if ($Category->isEmpty()) {
                return Helpers::failResponse('Category found');

            }

            return Helpers::successResponse('Category find', $Category);

        } catch (\Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * The function `updateCategory` in PHP updates a category with validation checks and error handling.
     *
     * @param Request request The `updateCategory` function is used to update a category based on the
     * provided request data. Here is a breakdown of the function:
     *
     * @return \Illuminate\Http\JsonResponse `updateCategory` function returns a JSON response based on the validation and update
     * process for a category.
     */
    public function updateCategory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return Helpers::ifValidatorFails($validator);
            }
            $Find_id = Category::where('id', $request->id)->first();
            if (empty($Find_id)) {
                return response()->json([
                    'status' => false,
                    'message' => 'id not match with the record',
                ]);
            }

            $CategoryExist = Category::where('id', '!=', $request->id)->where('name', $request->name)->first();

            if ($CategoryExist) {
                return Helpers::existResponse('Category name');

            }

            $Category = Category::findOrFail($request->id);
            $updated = $Category->update($request->all());
            if ($updated) {
                return Helpers::successResponse('Category updated', $Category);
            } else {
                return Helpers::failResponse('Category updated');
            }

        } catch (\Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * The function `deleteCategory` deletes a category by its ID and returns a JSON response
     * indicating success or failure.
     *
     * @param mixed id The `deleteCategory` function you provided is used to delete a category based on the
     * given `id`. The function first tries to find the category with the specified `id` using
     * `Category::findOrFail()`. If the category is found, it is deleted using
     * `->delete()`.
     *
     * @return \Illuminate\Http\JsonResponse `deleteCategory` function is returning a JSON response. If the deletion is
     * successful, it returns a JSON response with status true and a success message 'Category
     * deleted successfully'. If the deletion fails, it returns a JSON response with status false and
     * an error message 'Failed to delete Category'. If an exception occurs during the deletion
     * process, it returns a JSON response with an error message 'Failed to delete
     */
    public function deleteCategory(Request $request)
    {
        try {
            $Category = Category::findOrFail($request->id);
            if ($Category->delete()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Category deleted successfully',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to delete Category',
                ], 500);
            }
        } catch (\Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    //SubCategory

    /**
     * The function `createSubCategory` in PHP validates and creates a new subcategory, handling errors
     * and returning appropriate responses.
     *
     * @param Request request The `createSubCategory` function takes a `Request` object as a parameter.
     * This `Request` object likely contains the data needed to create a new subcategory, such as the
     * name of the subcategory and the category ID it belongs to.
     *
     * @return \Illuminate\Http\JsonResponse `createSubCategory` function returns a JSON response based on the validation and
     * creation process of a subcategory.
     */
    public function createSubCategory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'category_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return Helpers::ifValidatorFails($validator);
            }

            $SubCategoryExist = SubCategory::where('name', $request->name)->where('category_id', $request->category_id)->first();
            if ($SubCategoryExist) {
                return Helpers::existResponse('SubCategory name');

            }

            $SubCategory = SubCategory::create($request->all());
            if ($SubCategory) {
                return Helpers::createResponse('SubCategory created', $SubCategory);
            } else {
                return Helpers::failResponse('SubCategory created');
            }

        } catch (\Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * The function `readSubCategory` retrieves all subcategories and returns a JSON response with the
     * data or an error message if retrieval fails.
     *
     * @return \Illuminate\Http\JsonResponse function is returning a JSON response. If the SubCategory collection is empty, it
     * will return a JSON response with a status of false and a message indicating that no SubCategory
     * was found. If the SubCategory collection is not empty, it will return a JSON response with a
     * status of true, a success message, and the SubCategory data. If an exception occurs during the
     * process, it will return
     */
    public function readSubCategory()
    {
        try {
            $subCategories = SubCategory::with('category')->get();
            if ($subCategories->isEmpty()) {
                return Helpers::failResponse('subCategories found');
            }
            return Helpers::successResponse('subCategories find', $subCategories);

        } catch (\Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * The function `updateSubCategory` in PHP validates and updates a subcategory record, handling
     * errors and returning appropriate JSON responses.
     *
     * @param Request request The `updateSubCategory` function is used to update a subcategory based on
     * the provided request data. Let me explain the process step by step:
     *
     * @return \Illuminate\Http\JsonResponse function `updateSubCategory` is returning a JSON response based on the outcome of the
     * update operation. Here are the possible return scenarios:
     */
    public function updateSubCategory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'category_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return Helpers::ifValidatorFails($validator);
            }

            $Find_id = SubCategory::where('id', $request->id)->first();
            if (empty($Find_id)) {
                return response()->json([
                    'status' => false,
                    'message' => 'id not match with the record',
                ]);
            }

            $SubCategoryExist = SubCategory::where('id', '!=', $request->id)->where('name', $request->name)->where('category_id', $request->category_id)->first();

            if ($SubCategoryExist) {
                return Helpers::existResponse('SubCategory name');

            }

            $SubCategory = SubCategory::findOrFail($request->id);
            $updated = $SubCategory->update($request->all());

            if ($updated) {
                return Helpers::successResponse('SubCategory updated', $SubCategory);
            } else {
                return Helpers::failResponse('SubCategory updated');
            }
        } catch (\Exception $e) {
            return Helpers::catchResponse($e);
        }
    }
    /**
     * The function `deleteSubCategory` deletes a subcategory by its ID and returns a JSON response
     * indicating success or failure.
     *
     * @param mixed id The code you provided is a PHP function that deletes a SubCategory based on the given ID.
     * It uses Laravel's Eloquent ORM to find and delete the SubCategory.
     *
     * @return \Illuminate\Http\JsonResponse `deleteSubCategory` function is returning a JSON response. If the deletion of the
     * SubCategory is successful, it returns a JSON response with status true and a success message. If the
     * deletion fails, it returns a JSON response with status false and an error message. If an exception
     * is caught during the deletion process, it returns a JSON response with an error message and the
     * exception message.
     */

    public function deleteSubCategory(Request $request)
    {
        try {
            $SubCategory = SubCategory::findOrFail($request->id);
            if ($SubCategory->delete()) {
                return response()->json([
                    'status' => true,
                    'message' => 'SubCategory deleted successfully',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to delete SubCategory',
                ], 500);
            }
        } catch (\Exception $e) {
            return Helpers::catchResponse($e);
        }
    }
}
