<?php 
namespace App\Repositories\Eloquent;

use App\Models\FormatReponse;
use App\Repositories\Interfaces\FormatReponseRepositoryInterface;

class FormatReponseRepository implements FormatReponseRepositoryInterface
{
    public function getAll()
    {
        return FormatReponse::all();
    }

    public function getById($id)
    {
        $formatReponseId = FormatReponse::findOrFail($id);
        
        return $formatReponseId;
    }

    public function create(array $data)
    {
        
        return FormatReponse::create($data);
    }

    public function update($id, array $data)
    {
        $formatReponse = FormatReponse::findOrFail($id);
        $formatReponse->update($data);
        return $formatReponse;
    }

    public function delete($id)
    {
        return FormatReponse::destroy($id);
    }
}

?>