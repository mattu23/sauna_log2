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

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    
    //ユーザーの新規登録
    public function signUp(CreateUserRequest $request)
    {
      try {
          $user = $this->userService->createUser($request->validated());
          return response()->json($user, 201);
      } catch(\App\Exceptions\CustomException $e) {
          return response()->json(['message' => $e->getMessage()], 500);
      }
    }

    //ユーザーのログイン
    public function signIn(LoginRequest $request)
     {
        try {
            $user = $this->userService->loginUser($request->validated());
            // Sanctumトークンの生成と返却
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json(['token' => $token, 'user' => $user], 200);
        } catch(\App\Exceptions\AuthenticationException $e) {
            return response()->json(['message' => $e->getMessage()], 401); 
        } 
      }

    //ユーザーのログアウト
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'ログアウトしました。'], 200);
        } catch(\Exception $e) {
            // ログアウト処理中に何らかの問題が発生した場合
            return response()->json(['message' => 'ログアウト処理中に問題が発生しました。'], 500);
        }
    }

    //ログイン中のユーザー情報を取得する
    public function get(Request $request)
    {
      try {
          $userId = $request->user()->id;
          $user = $this->userService->getUserById($userId);
          return response()->json($user);
      } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
          return response()->json(['message' => 'ユーザーが見つかりません。もう一度最初からお試しください。'], 404);
      }
    }

    //ユーザー情報（PWを除く）の編集
    public function update(UpdateUserRequest $request)
    {
      try {
          $user = $this->userService->updateUser(Auth::id(), $request->validated());
          return response()->json($user, 200);
      } catch(\App\Exceptions\CustomException $e) {
          return response()->json(['message' => $e->getMessage()], 500);
      }
    }

    //ユーザーパスワードの編集
    public function updatePassword(updatePasswordRequest $request)
    {
      try {
          $currentPassword = $request->input('password');
          $newPassword = $request->input('newPassword');
          $user = $this->userService->updatePassword(Auth::id(), $currentPassword, $newPassword);
          return response()->json(['message'=> 'パスワードが更新されました。'], 200); 
      } catch(\App\Exceptions\InvalidPasswordException $e) {
          return response()->json(['message' => $e->getMessage()], 400);
      }
    }

    //ユーザー自体の削除
    public function delete()
    {
      try {
          $this->userService->deleteUser(Auth::id());
          return response()->json(['message'=> 'ユーザーが削除されました。'], 200);
      } catch(\App\Exceptions\CustomException $e) {
          return response()->json(['message' => $e->getMessage()], 500);
      }
    }

}