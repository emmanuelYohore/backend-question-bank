<?php 
namespace App\Repositories\Interfaces;

interface ItemRepositoryInterface
{
    public function getAll();
    public function getById($id);
   
    public function getOneItemForUserId($userId, $ItemId);
    public function getAllItemForUserId($id);

    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}

?>