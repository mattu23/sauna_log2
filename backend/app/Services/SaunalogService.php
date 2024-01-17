<?php 

namespace App\Services;

use App\Models\Saunalog;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;



class SaunalogService 
{
    public function getLogsByUser($userId): Collection 
    {
      return Saunalog::where('userId', $userId)->get();
    }

    public function findOne($id): ?Saunalog
    {
      return Saunalog::find($id);
    }

    public function create(array $data, User $user): Saunalog
    {
      $saunalog = new Saunalog($data);
      $saunalog->user()->associate($user);
      $saunalog->save();

      return $saunalog;
    }

    public function updateSaunalog($id, array $data): Saunalog
    {
      $saunalog = Saunalog::find($id);
      $saunalog->update($data);
      return $saunalog;
    }

    public function delete($id): bool
    {
      $saunalog = Saunalog::find($id);
      return $saunalog->delete();
    }


}