<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Models\BrandContacts;
use App\Models\Groups;
use App\Models\Leads;
use App\Models\Segments;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helpers;


class ContactsController extends Controller
{
    /**
     * The function `addContact` in PHP validates input data, creates a new contact record, and returns
     * a JSON response with the status and contact ID.
     *
     * @param Request request The `addContact` function is a PHP function that receives a request object
     * as a parameter. The request object contains data sent to the server, typically from a form
     * submission or an API call.
     *
     * @return \Illuminate\Http\JsonResponse `addContact` function returns a JSON response. If the input validation fails, it
     * returns a JSON response with status 0 and the validation errors. If the contact is successfully
     * added, it returns a JSON response with status 1, a success message, and the ID of the newly
     * created contact. If an exception occurs during the process, it returns a JSON response with
     * status 0, an
     */
    public function addContact(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'business_id' => 'required',
                'name' => 'required',
            ]);

            if ($validator->fails()) {
                 return Helpers::ifValidatorFails($validator);
            }

            // Add the contacts
            $contact = BrandContacts::create($request->all());

            return response()->json(['status' => 1, 'message' => 'Contact added successfully', 'contact_id' => $contact->id]);

        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * The `getAllContacts` function retrieves all contacts and returns a JSON response indicating success or failure.
     *
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with status 1 and the list of contacts on success,
     * or status 0 with an error message on failure.
     */
    public function getAllContacts()
    {
        try {
            // Retrieve all contacts
            $contacts = BrandContacts::all();

            return response()->json(['status' => 1, 'contacts' => $contacts]);

        } catch (Exception $e) {
             return Helpers::catchResponse($e);
        }
    }

    /**
     * The `getContact` function retrieves a contact by its ID and returns a JSON response indicating success or failure.
     *
     * @param integer $id The ID of the contact to retrieve.
     *
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with status 1 and the contact details on success,
     * or status 0 with an error message on failure.
     */
    public function getContact($id)
    {
        try {
            // Retrieve the contact by ID
            $contact = BrandContacts::find($id);

            if (!$contact) {
                return response()->json(['status' => 0, 'message' => 'Contact not found']);
            }

            return response()->json(['status' => 1, 'contact' => $contact]);

        } catch (Exception $e) {
             return Helpers::catchResponse($e);
        }
    }

    /**
     * The `updateContact` function updates an existing contact by its ID and returns a JSON response indicating success or failure.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request containing updated contact data.
     * @param integer $id The ID of the contact to update.
     *
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with status 1 and the updated contact details on success,
     * or status 0 with an error message on failure.
     */
    public function updateContact(Request $request, $id)
    {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'business_id' => 'required|integer',
                'group_id' => 'required|integer',
                'segment_id' => 'required|integer',
                'name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:20',
                'location' => 'nullable|string|max:255',
                'age' => 'nullable|integer',
                'email' => 'nullable|email|max:255',
                'type' => 'required|string|max:50',
            ]);

            if ($validator->fails()) {
                 return Helpers::ifValidatorFails($validator);
            }

            // Find the contact by ID
            $contact = BrandContacts::find($id);

            if (!$contact) {
                return response()->json(['status' => 0, 'message' => 'Contact not found']);
            }

            // Update the contact
            $contact->update($request->all());

            return response()->json(['status' => 1, 'message' => 'Contact updated successfully', 'contact' => $contact]);

        } catch (Exception $e) {
             return Helpers::catchResponse($e);
        }
    }

    /**
     * The `getContactsByBrandId` function retrieves all contacts associated with a specific brand ID and returns a JSON response indicating success or failure.
     *
     * @param integer $business_id The ID of the brand for which the contacts need to be retrieved.
     *
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with status 1 and the list of contacts on success,
     * or status 0 with an error message on failure.
     */
    public function getContactsByBrandId($business_id)
    {
        try {
            // Retrieve contacts by business_id
            $contacts = BrandContacts::where('business_id', $business_id)->get();

            if ($contacts->isEmpty()) {
                return response()->json(['status' => 0, 'message' => 'No contacts found for the specified brand ID']);
            }

            return response()->json(['status' => 1, 'contacts' => $contacts]);

        } catch (Exception $e) {
             return Helpers::catchResponse($e);
        }
    }

    /**
     * The function `importBulkContacts` processes a CSV file containing contact information, validates
     * the data, and saves valid records to the database with a limit of 100 records.
     *
     * @param Request request The `importBulkContacts` function is designed to handle the bulk import
     * of contacts from a CSV file. It takes a `Request` object as a parameter, which likely contains
     * the uploaded CSV file with the key `contacts_list`.
     *
     * @return \Illuminate\Http\JsonResponse function `importBulkContacts` returns a JSON response with status, message, and
     * error information based on the outcome of processing the bulk contacts import request.
     */
    public function importBulkContacts(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'contacts_list' => 'required|file|mimes:csv',
            ]);

            if ($validator->fails()) {
                 return Helpers::ifValidatorFails($validator);
            }

            // Load the CSV file
            $file = $request->file('contacts_list');
            $fileHandle = fopen($file->getRealPath(), 'r');

            // Initialize counters and storage for valid rows
            $validRows = [];
            $rowCount = 0;
            $header = fgetcsv($fileHandle); // Assuming the first row is the header

            // Check if the header has required columns
            if (!in_array('business_id', $header) || !in_array('name', $header)) {
                return response()->json(['status' => 0, 'error' => 'Invalid file format']);
            }

            // Process each row in the CSV
            while (($row = fgetcsv($fileHandle)) !== false) {
                $rowCount++;
                $rowData = array_combine($header, $row);

                // Skip rows where business_id or name is empty
                if (empty($rowData['business_id']) || empty($rowData['name'])) {
                    continue;
                }

                // Add valid rows to the array
                $validRows[] = $rowData;

                // Stop processing if more than 100 valid rows are found
                if (count($validRows) > 100) {
                    return response()->json([
                        'status' => 0,
                        'error' => 'Large file size found. Only 100 records are allowed.',
                    ]);
                }
            }

            fclose($fileHandle);

            // Save valid records to the database
            foreach ($validRows as $rowData) {
                BrandContacts::create($rowData);
            }

            return response()->json(['status' => 1, 'message' => 'Contacts added successfully']);

        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * The function `addGroups` in PHP validates input data, creates a new group based on the input,
     * and returns a JSON response with the status and relevant messages.
     *
     * @param Request request The `addGroups` function is designed to add a new group based on the
     * provided request data. Here is a breakdown of the function:
     *
     * @return \Illuminate\Http\JsonResponse `addGroups` function returns a JSON response with different keys based on the
     * outcome of the operation:
     */
    public function addGroups(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'business_id' => 'required|integer',
                'name' => 'required|string|max:255',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                 return Helpers::ifValidatorFails($validator);
            }

            // Create a new group
            $group = Groups::create($request->only('business_id', 'name', 'status'));

            return response()->json(['status' => 1, 'message' => 'Group created successfully', 'group' => $group]);

        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * The function `editGroup` in PHP validates and updates a group's information based on the
     * provided request data.
     *
     * @param Request request The `editGroup` function you provided is responsible for updating a group
     * based on the input provided in the request. It first validates the input data using Laravel's
     * Validator class to ensure that the data meets the specified criteria. If the validation fails,
     * it returns a JSON response with the validation errors.
     * @param integer id The `id` parameter in the `editGroup` function represents the unique identifier of the
     * group that you want to edit. This identifier is used to find the specific group in the database
     * that needs to be updated with the new information provided in the request.
     *
     * @return \Illuminate\Http\JsonResponse `editGroup` function returns a JSON response with different possible structures
     * based on the execution flow:
     */
    public function editGroup(Request $request, $id)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'business_id' => 'sometimes|required|integer',
                'name' => 'sometimes|required|string|max:255',
                'status' => 'sometimes|required|boolean',
            ]);

            if ($validator->fails()) {
                 return Helpers::ifValidatorFails($validator);
            }

            // Find the group
            $group = Groups::find($id);
            if (!$group) {
                return response()->json(['status' => 0, 'error' => 'Group not found']);
            }

            // Update the group
            $group->update($request->only('business_id', 'name', 'status'));

            return response()->json(['status' => 1, 'message' => 'Group updated successfully', 'group' => $group]);

        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * The function `deleteGroup` deletes a group by its ID and returns a JSON response indicating
     * success or failure.
     *
     * @param integer id The `deleteGroup` function you provided is responsible for deleting a group based on
     * the given `id`. The `id` parameter represents the unique identifier of the group that needs to be
     * deleted from the database.
     *
     * @return \Illuminate\Http\JsonResponse `deleteGroup` function returns a JSON response. If the group with the specified ID is
     * found and successfully deleted, it returns a JSON response with status 1 and a message indicating
     * that the group was deleted successfully. If the group is not found, it returns a JSON response
     * with status 0 and an error message stating that the group was not found. If an exception occurs
     * during the deletion process
     */
    public function deleteGroup($id)
    {
        try {
            // Find the group
            $group = Groups::find($id);
            if (!$group) {
                return response()->json(['status' => 0, 'error' => 'Group not found']);
            }

            // Delete the group
            $group->delete();

            return response()->json(['status' => 1, 'message' => 'Group deleted successfully']);

        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * The function `getGroups` retrieves a list of segments from the database.
     *
     * @param \Illuminate\Http\Request $request The request object containing any filters or parameters for the query (if applicable).
     *
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with status 1 and the list of segments if the retrieval is successful,
     * or status 0 with an error message if the retrieval fails.
     */
    public function getGroups($id = null)
    {
        try {
            if ($id) {
                // Get a specific group
                $group = Groups::find($id);
                if (!$group) {
                    return response()->json(['status' => 0, 'error' => 'Group not found']);
                }
                return response()->json(['status' => 1, 'group' => $group]);
            } else {
                // Get all groups
                $groups = Groups::all();
                return response()->json(['status' => 1, 'groups' => $groups]);
            }

        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * The function `createSegment` creates a new segment in the database and returns a JSON response.
     *
     * @param \Illuminate\Http\Request $request The request object containing the data for the new segment.
     *
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with status 1 if the segment is successfully created,
     * or status 0 with an error message if the creation fails.
     */
    public function createSegment(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'business_id' => 'required|integer',
                'title' => 'required|string|max:255',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                 return Helpers::ifValidatorFails($validator);
            }

            // Create a new segment
            $segment = Segments::create($request->only('business_id', 'title', 'status'));

            return response()->json(['status' => 1, 'message' => 'Segment created successfully', 'segment' => $segment]);

        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * The function `getSegments` retrieves a list of segments from the database.
     *
     * @param \Illuminate\Http\Request $request The request object containing any filters or parameters for the query (if applicable).
     *
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with status 1 and the list of segments if the retrieval is successful,
     * or status 0 with an error message if the retrieval fails.
     */
    public function getSegments($id = null)
    {
        try {
            if ($id) {
                // Get a specific segment
                $segment = Segments::find($id);
                if (!$segment) {
                    return response()->json(['status' => 0, 'error' => 'Segment not found']);
                }
                return response()->json(['status' => 1, 'segment' => $segment]);
            } else {
                // Get all segments
                $segments = Segments::all();
                return response()->json(['status' => 1, 'segments' => $segments]);
            }

        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * The function `updateSegment` updates an existing segment in the database based on the provided ID and returns a JSON response.
     *
     * @param \Illuminate\Http\Request $request The request object containing the updated data for the segment.
     * @param integer $id The ID of the segment to be updated.
     *
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with status 1 if the segment is successfully updated,
     * or status 0 with an error message if the update fails.
     */
    public function updateSegment(Request $request, $id)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'business_id' => 'sometimes|required|integer',
                'title' => 'sometimes|required|string|max:255',
                'status' => 'sometimes|required|boolean',
            ]);

            if ($validator->fails()) {
                 return Helpers::ifValidatorFails($validator);
            }

            // Find the segment
            $segment = Segments::find($id);
            if (!$segment) {
                return response()->json(['status' => 0, 'error' => 'Segment not found']);
            }

            // Update the segment
            $segment->update($request->only('business_id', 'title', 'status'));

            return response()->json(['status' => 1, 'message' => 'Segment updated successfully', 'segment' => $segment]);

        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * The function `deleteSegment` deletes a segment by its ID and returns a JSON response indicating success or failure.
     *
     * @param integer $id The unique identifier of the segment that needs to be deleted.
     *
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with status 1 if the segment is successfully deleted,
     * or status 0 with an error message if the segment is not found or the deletion fails.
     */
    public function deleteSegment($id)
    {
        try {
            // Find the segment
            $segment = Segments::find($id);
            if (!$segment) {
                return response()->json(['status' => 0, 'error' => 'Segment not found']);
            }

            // Delete the segment
            $segment->delete();

            return response()->json(['status' => 1, 'message' => 'Segment deleted successfully']);

        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }

    /**
     * The `createLead` function creates a new lead in the database and returns a JSON response indicating success or failure.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object containing lead data.
     *
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with status 1 and the created lead's details on success,
     * or status 0 with an error message on failure.
     */
    public function createLead(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'business_id' => 'required|integer',
                'name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:15',
                'location' => 'nullable|string|max:255',
                'age' => 'nullable|integer',
                'email' => 'nullable|email|max:255',
                'type' => 'nullable|string|max:50',
            ]);

            if ($validator->fails()) {
                 return Helpers::ifValidatorFails($validator);
            }

            // Create the lead
            $lead = Leads::create($request->all());

            return response()->json(['status' => 1, 'message' => 'Lead created successfully', 'lead' => $lead]);

        } catch (Exception $e) {
             return Helpers::catchResponse($e);
        }
    }

    /**
     * The `getLeads` function retrieves all leads from the database and returns a JSON response with the list of leads.
     *
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with status 1 and the list of leads on success,
     * or status 0 with an error message on failure.
     */
    public function getLeads()
    {
        try {
            // Retrieve all leads
            $leads = Leads::all();

            return response()->json(['status' => 1, 'leads' => $leads]);

        } catch (Exception $e) {
             return Helpers::catchResponse($e);
        }
    }

    /**
     * The `getLeadById` function retrieves a lead by its ID and returns a JSON response with the lead's details.
     *
     * @param integer $id The ID of the lead to retrieve.
     *
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with status 1 and the lead's details on success,
     * or status 0 with an error message if the lead is not found.
     */
    public function getLeadById($id)
    {
        try {
            // Retrieve lead by ID
            $lead = Leads::find($id);

            if (!$lead) {
                return response()->json(['status' => 0, 'message' => 'Lead not found']);
            }

            return response()->json(['status' => 1, 'lead' => $lead]);

        } catch (Exception $e) {
             return Helpers::catchResponse($e);
        }
    }

    /**
     * The `updateLead` function updates an existing lead in the database by its ID and returns a JSON response indicating success or failure.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object containing lead data.
     * @param integer $id The ID of the lead to update.
     *
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with status 1 and a success message on successful update,
     * or status 0 with an error message if the lead is not found or the update fails.
     */
    public function updateLead(Request $request, $id)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'business_id' => 'required|integer',
                'name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:15',
                'location' => 'nullable|string|max:255',
                'age' => 'nullable|integer',
                'email' => 'nullable|email|max:255',
                'type' => 'nullable|string|max:50',
            ]);

            if ($validator->fails()) {
                 return Helpers::ifValidatorFails($validator);
            }

            // Find the lead by ID
            $lead = Leads::find($id);

            if (!$lead) {
                return response()->json(['status' => 0, 'message' => 'Lead not found']);
            }

            // Update the lead
            $lead->update($request->all());

            return response()->json(['status' => 1, 'message' => 'Lead updated successfully', 'lead' => $lead]);

        } catch (Exception $e) {
             return Helpers::catchResponse($e);
        }
    }

    /**
     * The `deleteLead` function deletes a lead from the database by its ID and returns a JSON response indicating success or failure.
     *
     * @param integer $id The ID of the lead to delete.
     *
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with status 1 and a success message if the lead is successfully deleted,
     * or status 0 with an error message if the lead is not found or the deletion fails.
     */
    public function deleteLead($id)
    {
        try {
            // Find the lead by ID
            $lead = Leads::find($id);

            if (!$lead) {
                return response()->json(['status' => 0, 'message' => 'Lead not found']);
            }

            // Delete the lead
            $lead->delete();

            return response()->json(['status' => 1, 'message' => 'Lead deleted successfully']);

        } catch (Exception $e) {
             return Helpers::catchResponse($e);
        }
    }

    /**
     * The `getLeadsByBrandId` function retrieves all contacts associated with a specific brand ID and returns a JSON response indicating success or failure.
     *
     * @param integer $business_id The ID of the brand for which the contacts need to be retrieved.
     *
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with status 1 and the list of contacts on success,
     * or status 0 with an error message on failure.
     */
    public function getLeadsByBrandId($business_id)
    {
        try {
            // Retrieve contacts by business_id
            $contacts = Leads::where('business_id', $business_id)->get();

            if ($contacts->isEmpty()) {
                return response()->json(['status' => 0, 'message' => 'No contacts found for the specified brand ID']);
            }

            return response()->json(['status' => 1, 'contacts' => $contacts]);

        } catch (Exception $e) {
             return Helpers::catchResponse($e);
        }
    }

    /**
     * The function `importBulkLeads` processes a CSV file containing contact information, validates
     * the data, and saves valid records to the database with a limit of 100 records.
     *
     * @param Request request The `importBulkLeads` function is designed to handle the bulk import
     * of leads from a CSV file. It takes a `Request` object as a parameter, which likely contains
     * the uploaded CSV file with the key `leads_list`.
     *
     * @return \Illuminate\Http\JsonResponse function `importBulkLeads` returns a JSON response with status, message, and
     * error information based on the outcome of processing the bulk leads import request.
     */
    public function importBulkLeads(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'leads_list' => 'required|file|mimes:csv',
            ]);

            if ($validator->fails()) {
                 return Helpers::ifValidatorFails($validator);
            }

            // Load the CSV file
            $file = $request->file('leads_list');
            $fileHandle = fopen($file->getRealPath(), 'r');

            // Initialize counters and storage for valid rows
            $validRows = [];
            $rowCount = 0;
            $header = fgetcsv($fileHandle); // Assuming the first row is the header

            // Check if the header has required columns
            if (!in_array('business_id', $header) || !in_array('name', $header)) {
                return response()->json(['status' => 0, 'error' => 'Invalid file format']);
            }

            // Process each row in the CSV
            while (($row = fgetcsv($fileHandle)) !== false) {
                $rowCount++;
                $rowData = array_combine($header, $row);

                // Skip rows where business_id or name is empty
                if (empty($rowData['business_id']) || empty($rowData['name'])) {
                    continue;
                }

                // Add valid rows to the array
                $validRows[] = $rowData;

                // Stop processing if more than 100 valid rows are found
                if (count($validRows) > 100) {
                    return response()->json([
                        'status' => 0,
                        'error' => 'Large file size found. Only 100 records are allowed.',
                    ]);
                }
            }

            fclose($fileHandle);

            // Save valid records to the database
            foreach ($validRows as $rowData) {
                Leads::create($rowData);
            }

            return response()->json(['status' => 1, 'message' => 'Leads added successfully']);

        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }
}
