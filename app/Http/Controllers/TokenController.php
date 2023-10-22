<?php

namespace App\Http\Controllers;

use App\Models\UserToken;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenController extends Controller
{

    /**
     * @throws AuthorizationException
     */
    public function create(Request $request)
    {
        $this->authorize('create', UserToken::class);
        $newToken = $request->user()->createToken(now()->addDays(30));
        return response()->json([
            'message' => 'Token created successfully',
            'access_token' => 'Bearer '. $newToken->access_token,
            'expires_at' => $newToken->expires_at,
        ]);
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
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }

    }

}
