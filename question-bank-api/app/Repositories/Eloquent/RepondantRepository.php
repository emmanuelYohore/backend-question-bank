<?php 
namespace App\Repositories\Eloquent;

use App\Models\Repondant;
use App\Repositories\Interfaces\RepondantRepositoryInterface;

class RepondantRepository implements RepondantRepositoryInterface
{
    public function getAll()
    {
        return Repondant::all();
    }

    public function getById($id)
    {
        $repondantId = Repondant::findOrFail($id);
        if (empty($repondantId)) {
            return "Repondant pas trouvé";
        }
        return $repondantId;
    }

    public function create(array $data)
    {
        
        return Repondant::create($data);
    }

    public function update($id, array $data)
    {
        $repondant = Repondant::findOrFail($id);
        $repondant->update($data);
        return $repondant;
    }

    public function delete($id)
    {
        return Repondant::destroy($id);
    }
}

?>