<?php 


namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\AuthenticationException;
use App\Exceptions\SystemException;
use App\Exceptions\NotFoundException;



class UserService
{
    //新規ユーザー作成
    public function createUser(array $data): User
    {
      try {
        return User::createUser($data);
      } catch(\Exception $e) {
        throw new SystemException('ユーザー作成に失敗しました。');
      }
    }

    //ログイン
    public function loginUser(array $credentials)
    {
      if(!Auth::attempt($credentials)) {
        throw new AuthenticationException('認証に失敗しました。');
      }
      return Auth::user();
    }

    //ログイン中のユーザー情報を取得する
    public function getUserById($userId): User
    {
      $user = User::find($userId);
      if(!$user) {
        throw new NotFoundException('ユーザーが見つかりません');
      }
      return $user;
    }

    //ユーザー情報（PWを除く）の編集
    public function updateUser($userId, array $data): User
    {
      //getUserByIdで例外処理を記載しているのでここでは省略
      $user = $this->getUserById($userId);
      $user->update($data);
      return $user;
    }

    //パスワードの編集
    public function updatePassword($userId, $currentPassword, $newPassword)
    {
      $user = $this->getUserById($userId);

      if(!Hash::check($currentPassword, $user->password)) {
        throw new AuthenticationException('現在のパスワードが誤っています');
      }
      $user->password = Hash::make($newPassword);
      $user->save();
      return $user;
    }

    //ユーザーの削除
    public function deleteUser($userId)
    {
      //getUserByIdで例外処理を記載しているのでここでは省略
      $user = $this->getUserById($userId);
      $user->delete();
      return $user;
    }
}