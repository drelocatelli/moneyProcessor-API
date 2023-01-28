<?php

namespace App\Http\Controllers;

use App\Http\Requests\Resume\ResumeRequest;
use App\Repositories\ResumeRepository;
use Illuminate\Support\Facades\Auth;

class ResumeController extends Controller
{

    public function __construct(
        private ResumeRepository $repository,
    )
    {}
    
    public function index(ResumeRequest $request)
    {
        return response()->json($this->repository->getResume(Auth::id(), $request->validated()));
    }
    
}
