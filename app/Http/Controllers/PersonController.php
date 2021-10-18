<?php

namespace App\Http\Controllers;

use App\Repositories\PersonRepositoryInterface;
use App\Services\UpcomingBirthdaysService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PersonController extends Controller
{
    /**
     * Get list of persons with birthday info
     *
     * @param UpcomingBirthdaysService $service
     * @return JsonResponse
     */
    public function index(UpcomingBirthdaysService $service): JsonResponse
    {
        try {
            return response()->json(['data' => $service->getPersonsList()]);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create new person
     *
     * @param Request $request
     * @param PersonRepositoryInterface $repository
     * @return JsonResponse
     */
    public function create(Request $request, PersonRepositoryInterface $repository): JsonResponse
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'birthdate' => ['required', 'date_format:Y-m-d', 'before_or_equal:yesterday'],
                'timezone' => ['required', 'timezone'],
            ]);

            $repository->add($request->input());

            return response()->json(['message' => 'success'], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (Throwable $e) {
            return response()->json(['message' => 'error',], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
