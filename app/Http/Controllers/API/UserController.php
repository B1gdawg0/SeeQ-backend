<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateImageRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\IdResource;
use App\Http\Resources\ImageUrlResource;
use App\Http\Resources\ShopResource;
use App\Http\Resources\UrlResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function index()
    {
        Gate::authorize('viewAny', User::class);
        $users = $this->userRepository->getAll();
        return UserResource::collection($users);
    }

    public function getAllCustomerWithTrashedPaginate()
    {
        Gate::authorize('viewAny', User::class);
        $users = $this->userRepository->getAllCustomerWithTrashedPaginate();
        return UserResource::collection($users);
    }

    public function getAllCustomerWithTrashed()
    {
        Gate::authorize('viewAny', User::class);
        $users = $this->userRepository->getAllCustomerWithTrashed();

        if ($users->isEmpty()) {
            return response()->json([
                'message' => 'No customer found'
            ])->setStatusCode(404);
        }

        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        Gate::authorize('view', $user);
        return new UserResource($user);
    }
    public function update(UpdateUserRequest $request, User $user)
    {
        Gate::authorize('update', $user);
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone
        ]);
        return IdResource::make($user);
    }
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully'
        ])->setStatusCode(200);
    }

    public function restore($id)
    {
        $user = $this->userRepository->getByIdWithTrashed($id);
        Gate::authorize('restore', $user);
        $user->restore();
        return response()->json([
            'message' => 'User restored successfully'
        ])->setStatusCode(200);
    }

    public function updatePassword(UpdatePasswordRequest $request, User $user)
    {
        Gate::authorize('update', $user);
        if ($user->login_by != 'default') {
            return response()->json([
                'message' => 'Unauthorized'
            ])->setStatusCode(401);
        }

        $new_password = $request->new_password;
        if (Hash::check($new_password, $user->password)) {
            return response()->json([
                'message' => 'New password must differ from the old'
            ])->setStatusCode(400);
        }
        $user->update([
            'password' => Hash::make($new_password)
        ]);
        return IdResource::make($user);
    }

    public function updateAvatar(UpdateImageRequest $request, User $user)
    {
        Gate::authorize('update', $user);
        if ($request->hasFile('image')) {
            $file = $request->image;
            $filename = now()->format('Y-m-d_H:i:s.u') . '.png';
            $path = 'customers/'. $user->id .'/images/avatars/'. $filename;
            Storage::disk('s3')->put($path, file_get_contents($file), 'private');
            $uri = str_replace('/', '+', $path);
            $user->update([
                'image_url' => env("APP_URL") . '/api/images/' . $uri
            ]);
        }
        return UrlResource::make((object) [
            'url' => $user->image_url
        ]);
    }

    public function showShop(User $user)
    {
       if (!$user) {
           return response()->json([
               'error' => 'Shop not found'
           ], 404);
       }

        $shop = $user->shop()->first();
       if (!$shop) {
           return response()->json([
               'error' => 'Shop not found'
           ], 404);
       }
       return ShopResource::make($shop)->response()->setStatusCode(200);
    }
}
