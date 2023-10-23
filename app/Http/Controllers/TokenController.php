<?php

namespace App\Http\Controllers;

use App\Models\UserToken;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class TokenController extends Controller
{

    /**
     * @throws AuthorizationException
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $this->authorize('create', UserToken::class);
            $newToken = $request->user()->createCustomToken(now()->addDays(30));
            return response()->json($newToken)->setStatusCode(201);
        }catch (AuthorizationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ])->setStatusCode(403);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
            ])->setStatusCode(500);
        }

    }

    /**
     * @throws AuthorizationException
     */
    public function destroy($id): JsonResponse
    {
        try {
            $token = UserToken::findOrFail($id);
            $this->authorize('delete', $token);
            $token->delete();
            return response()->json([
                'message' => 'Token deleted successfully',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Token not found',
            ])->setStatusCode(404);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ])->setStatusCode(403);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
            ])->setStatusCode(500);
        }

    }

}
