<?php

namespace App\Http\Controllers;

use App\Actions\GuestPageAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GuestPageController extends Controller
{
    /**
     * @return JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getHomePage (): JsonResponse
    {
        return response()->json(
            (new GuestPageAction())->getHomePage()
        );
    }
}
