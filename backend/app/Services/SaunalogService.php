<?php 

namespace App\Services;

use App\Models\Saunalog;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;



class SaunalogService 
{

    //ユーザーに紐づくサウナログ一覧の表示
    public function getLogsByUser($userId): Collection 
    {
      return Saunalog::where('userId', $userId)->get();
    }


    //特定のサウナログの表示
    public function getLogById($id): ?Saunalog
    {
      return Saunalog::find($id);
    }

    //サウナログの新規作成
    public function createLog(array $data, User $user): Saunalog
    {
      $saunalog = new Saunalog($data);
      $saunalog->user()->associate($user);
      $saunalog->save();

      return $saunalog;
    }

    //サウナログの編集
    public function updateLogById($id, array $data): Saunalog
    {
      $saunalog = Saunalog::find($id);
      $saunalog->update($data);
      return $saunalog;
    }

    //サウナログの削除
    public function deleteLogById($id): bool
    {
      $saunalog = Saunalog::find($id);
      return $saunalog->delete();
    }


}