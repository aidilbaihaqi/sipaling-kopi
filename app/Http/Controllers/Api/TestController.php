<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class TestController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin",
     *     summary="Admin test route",
     *     tags={"RBAC"},
     *     security={{"api_key":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Welcome, Admin!",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function admin()
    {
        return response()->json(['message' => 'Welcome, Admin!']);
    }

    /**
     * @OA\Get(
     *     path="/api/kitchen",
     *     summary="Kitchen manager test route",
     *     tags={"RBAC"},
     *     security={{"api_key":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Welcome, Kitchen Manager!",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function kitchen()
    {
        return response()->json(['message' => 'Welcome, Kitchen Manager!']);
    }

    /**
     * @OA\Get(
     *     path="/api/cashier",
     *     summary="Cashier test route",
     *     tags={"RBAC"},
     *     security={{"api_key":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Welcome, Cashier!",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function cashier()
    {
        return response()->json(['message' => 'Welcome, Cashier!']);
    }
}
