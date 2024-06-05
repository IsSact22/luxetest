<?php

namespace App\Http\Controllers;

use App\Contracts\AdminRateRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreAdminRateRequest;
use App\Http\Requests\UpdateAdminRateRequest;
use App\Http\Resources\AdminRateResource;
use App\Models\AdminRate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests as Precognitive;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class AdminRateController extends Controller
{
    public function __construct(protected AdminRateRepositoryInterface $adminRateRepository)
    {
        parent::__construct();
        $this->middleware(Precognitive::class)->only(['store', 'update']);
    }

    public function index(Request $request): Response
    {
        try {
            $this->authorize('viewAny', AdminRate::class);
            $adminRates = $this->adminRateRepository->getAll($request);
            $resource = AdminRateResource::collection($adminRates);

            return InertiaResponse::content('AdminRates/Index', ['resource' => $resource]);
        } catch (AuthorizationException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_UNAUTHORIZED]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', [
                'status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                'description' => $e->getMessage(),
            ]);
        }
    }

    public function create(Request $request): Response
    {
        try {
            $this->authorize('create', AdminRate::class);

            return InertiaResponse::content('AdminRates/Create');
        } catch (AuthorizationException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_UNAUTHORIZED]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    public function store(StoreAdminRateRequest $request): Response|RedirectResponse
    {
        try {
            $this->authorize('create', AdminRate::class);
            $payload = precognitive(static fn ($bail) => $request->validated());
            $this->adminRateRepository->newModel($payload);

            return to_route('admin-rates.index')->with('success', 'Rate has been created.');
        } catch (AuthorizationException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_UNAUTHORIZED]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    public function show(AdminRate $adminRate): Response
    {
        return Inertia::render('Errors/error', [
            'status' => ResponseAlias::HTTP_NOT_FOUND,
        ]);
    }

    public function edit(Request $request, int $id): Response
    {
        try {
            $adminRate = $this->adminRateRepository->getById($id);
            $this->authorize('update', $adminRate);
            $resource = new AdminRateResource($adminRate);

            return InertiaResponse::content('AdminRates/Edit', ['resource' => $resource]);
        } catch (AuthorizationException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_UNAUTHORIZED]);
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    public function update(UpdateAdminRateRequest $request, int $id): Response|RedirectResponse
    {
        try {
            $adminRate = $this->adminRateRepository->getById($id);
            $this->authorize('update', $adminRate);
            $payload = precognitive(static fn ($bail) => $request->validated());
            $this->adminRateRepository->updateModel($payload, $id);

            return to_route('admin-rates.index')->with('success', 'Rate has been updated.');
        } catch (AuthorizationException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_UNAUTHORIZED]);
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    public function destroy(Request $request, int $id): Response|RedirectResponse
    {
        try {
            $adminRate = $this->adminRateRepository->getById($id);
            $this->authorize('delete', $adminRate);
            $this->adminRateRepository->deleteModel($id);

            return to_route('admin-rates.index')->with('success', 'Rate has been deleted.');
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
}
