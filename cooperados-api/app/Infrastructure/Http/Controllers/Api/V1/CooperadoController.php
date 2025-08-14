<?php

namespace App\Infrastructure\Http\Controllers\Api\V1;

use App\Application\Cooperado\DTOs\{CreateCooperadoDTO, UpdateCooperadoDTO};
use App\Application\Cooperado\Services\{
    CreateCooperadoService,
    UpdateCooperadoService,
    DeleteCooperadoService,
    GetCooperadoService
};
use App\Infrastructure\Http\Requests\{StoreCooperadoRequest, UpdateCooperadoRequest};
use App\Infrastructure\Http\Resources\{CooperadoResource, CooperadoCollection};
use App\Domain\Cooperado\Enums\TipoPessoa;
use App\Domain\Cooperado\Exceptions\{DocumentoInvalidoException, DocumentoDuplicadoException};
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Exception;

class CooperadoController extends \Illuminate\Routing\Controller
{
    public function __construct(
        private CreateCooperadoService $createService,
        private UpdateCooperadoService $updateService,
        private DeleteCooperadoService $deleteService,
        private GetCooperadoService $getService
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $nome = $request->get('nome');
            $documento = $request->get('documento');
            $tipoPessoa = $request->get('tipo_pessoa');

            $tipoPessoaEnum = $tipoPessoa ? TipoPessoa::from($tipoPessoa) : null;

            $paginator = $this->getService->paginateWithFilters(
                $perPage,
                $nome,
                $documento,
                $tipoPessoaEnum
            );

            return response()->json([
                'data' => $paginator->items(),
                'meta' => [
                    'total' => $paginator->total(),
                    'per_page' => $paginator->perPage(),
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'from' => $paginator->firstItem(),
                    'to' => $paginator->lastItem(),
                ],
                'links' => [
                    'first' => $paginator->url(1),
                    'last' => $paginator->url($paginator->lastPage()),
                    'prev' => $paginator->previousPageUrl(),
                    'next' => $paginator->nextPageUrl(),
                ],
            ]);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar cooperados.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreCooperadoRequest $request): JsonResponse
    {
        try {
            $dto = CreateCooperadoDTO::fromArray($request->validated());
            $cooperado = $this->createService->execute($dto);

            return response()->json([
                'message' => 'Cooperado criado com sucesso.',
                'data' => $cooperado->toArray()
            ], Response::HTTP_CREATED);

        } catch (DocumentoDuplicadoException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_CONFLICT);

        } catch (DocumentoInvalidoException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar cooperado.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q');
            
            if (!$query) {
                return response()->json([
                    'data' => [],
                    'message' => 'Parâmetro de busca é obrigatório.'
                ], 400);
            }

            $cooperados = $this->getService->paginateWithFilters(15, $query);

            return response()->json([
                'data' => $cooperados->items(),
                'meta' => [
                    'total' => $cooperados->total(),
                    'per_page' => $cooperados->perPage(),
                    'current_page' => $cooperados->currentPage(),
                    'last_page' => $cooperados->lastPage(),
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar cooperados.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $cooperado = $this->getService->findById($id);

            if (!$cooperado) {
                return response()->json([
                    'message' => 'Cooperado não encontrado.'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'data' => $cooperado->toArray()
            ]);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar cooperado.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateCooperadoRequest $request, string $id): JsonResponse
    {
        try {
            $dto = UpdateCooperadoDTO::fromArray($request->validated());
            $cooperado = $this->updateService->execute($id, $dto);

            return response()->json([
                'message' => 'Cooperado atualizado com sucesso.',
                'data' => $cooperado->toArray()
            ]);

        } catch (DocumentoDuplicadoException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_CONFLICT);

        } catch (DocumentoInvalidoException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar cooperado.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $deleted = $this->deleteService->execute($id);

            if (!$deleted) {
                return response()->json([
                    'message' => 'Cooperado não encontrado.'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'message' => 'Cooperado excluído com sucesso.'
            ], Response::HTTP_NO_CONTENT);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir cooperado.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
