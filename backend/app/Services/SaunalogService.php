<?php 

namespace App\Services;

use App\Models\Saunalog;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use App\Exceptions\CustomException;
use App\Exceptions\NotFoundException;




class SaunalogService 
{

    //ユーザーに紐づくサウナログ一覧の表示
    public function getLogsByUser($userId): Collection 
    {
      try {
          return Saunalog::where('userId', $userId)->get();
      } catch(\Exception $e) {
          throw new CustomException('サウナログの一覧表示に失敗しました。');
      }
    }


    //特定のサウナログの表示
    public function getLogById($id): ?Saunalog
    {
      try {
          $saunalog = Saunalog::find($id);
          if(!$saunalog) {
            throw new NotFoundException('特定のサウナログが見つかりません。');
          }
          return $saunalog;
      } catch(\Exception $e) {
          throw new CustomException('サウナログの表示に失敗しました。');
      }
    }

    //サウナログの新規作成
    public function createLog(array $data, User $user): Saunalog
    {
      try {
          $saunalog = new Saunalog($data);
          $saunalog->user()->associate($user);
          $saunalog->save();
          return $saunalog;
      } catch(\Exception $e) {
          throw new CustomException('サウナログの新規作成に失敗しました。');
      }
    }

    //サウナログの編集
    public function updateLogById($id, array $data): Saunalog
    {
      try {
          $saunalog = Saunalog::find($id);
          if(!$saunalog) {
            throw new NotFoundException('編集対象のサウナログが見つかりません。');
          }
          $saunalog->update($data);
          return $saunalog;
      } catch(\Exception $e) {
          throw new CustomException('サウナログの編集に失敗しました。');
      }
    }

    //サウナログの削除
    public function deleteLogById($id): bool
    {
      try {
          $saunalog = Saunalog::find($id);
          if (!$saunalog) {
            throw new NotFoundException('削除対象のサウナログが見つかりません。');
         }
          return $saunalog->delete();
      } catch(\Exception $e) {
        throw new CustomException('サウナログの削除に失敗しました。');
      } 
    }


}