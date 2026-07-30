<?php 
namespace App\Repositories\Eloquent;

use App\Models\EnqueteBank;
use App\Repositories\Interfaces\EnqueteBankRepositoryInterface;

class EnqueteBankRepository implements EnqueteBankRepositoryInterface
{
    public function getAll()
    {
        return EnqueteBank::all();
    }

    public function getById($id)
    {
        $EnqueteBankId = EnqueteBank::findOrFail($id);
        if (empty($EnqueteBankId)) {
            return "EnqueteBank pas trouvé";
        }
        return $EnqueteBankId;
    }

    public function create(array $data)
    {
        
        return EnqueteBank::create($data);
    }

    public function update($id, array $data)
    {
        $EnqueteBank = EnqueteBank::findOrFail($id);
        $EnqueteBank->update($data);
        return $EnqueteBank;
    }

    public function delete($id)
    {
        return EnqueteBank::destroy($id);
    }
}

?>