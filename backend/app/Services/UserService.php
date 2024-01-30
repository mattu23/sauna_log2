<?php 


namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class UserService
{
    //新規ユーザー作成
    public function createUser(array $data): User
    {
      return User::createUser($data);
    }

    //ログイン
    public function loginUser(array $credentials)
    {
      if(!Auth::attempt($credentials)) {
        throw new \Exception('メールアドレスかパスワードが誤っています');
      }
      return Auth::user();
    }

    //ログイン中のユーザー情報を取得する
    public function getUserById($userId): User
    {
      $user = User::find($userId);
      if(!$user) {
        throw new ModelNotFoundException('ユーザーが見つかりません');
      }
      return $user;
    }

    //ユーザー情報（PWを除く）の編集
    public function updateUser($userId, array $data): User
    {
      $user = $this->getUserById($userId);
      $user->update($data);
      return $user;
    }

    //パスワードの編集
    public function updatePassword($userId, $currentPassword, $newPassword)
    {
      $user = $this->getUserById($userId);

      if(!Hash::check($currentPassword, $user->password)) {
        throw new \Exception('現在のパスワードが誤っています');
      }

      $user->password = Hash::make($newPassword);
      $user->save();
      return $user;
    }

    //ユーザーの削除
    public function deleteUser($userId)
    {
      $user = $this->getUserById($userId);
      $user->delete();
      return $user;
    }
}