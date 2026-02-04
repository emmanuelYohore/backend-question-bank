<?php 
namespace App\Repositories\Interfaces;

interface BankItemRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function getOneBankItemForUserId($userId, $enqueteId);
    public function getAllBankItemForUserId($userId);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}

?>