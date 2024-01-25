<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Services\UserService;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $userService;

    //
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    //ユーザーの新規登録
    public function signUp(CreateUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());
        return response()->json($user, 201);
    }

    //ユーザーのログイン
    public function signIn(LoginRequest $request)
     {
        $user = $this->userService->loginUser($request->validated());
        
        // Sanctumトークンの生成と返却
        $token = $user->createToken('authToken')->plainTextToken;
        return response()->json(['token' => $token, 'user' => $user], 200);
      }

    //ユーザーのログアウト
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'ログアウトしました。'], 200);
    }

    //ログイン中のユーザー情報を取得する
    public function getUserData(Request $request)
    {
      $userId = $request->user()->id;
      return $this->userService->getUserById($userId);
    }

    //ユーザー情報（PWを除く）の編集
    public function updateUserData(UpdateUserRequest $request)
    {
      $user = $this->userService->updateUserData(Auth::id(), $request->validated());
      return response()->json($user, 200);
    }

    //ユーザーパスワードの編集
    public function updateUserPassword(updatePasswordRequest $request)
    {
      $currentPassword = $request->input('password');
      $newPassword = $request->input('newPassword');

      $user = $this->userService->updateUserPassword(Auth::id(), $currentPassword, $newPassword);
      return response()->json(['message'=> 'パスワードが更新されました。'], 200);
    }

    //ユーザー自体の削除
    public function deleteUser()
    {
      $this->userService->deleteUser(Auth::id());
      return response()->json(['message'=> 'ユーザーが削除されました。'], 200);
    }

}