<?php 
namespace App\Repositories\Interfaces;

interface EnqueteRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function getOneEnqueteForUserId($userId, $enqueteId);
    public function getAllEnqueteForUserId($userId);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}

?>