<?php


namespace App\Modules\Settings\Customer\Services;

use App\Modules\Settings\Customer\Models\Customer;

class CustomerService
{
    public function getAll()
    {
        return Customer::latest()->get();
    }

    public function create(array $data)
    {
        return Customer::create($data);
    }

    public function find(int $id)
    {
        return Customer::findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        $customer = $this->find($id);

        $customer->update($data);

        return $customer;
    }

    public function delete(int $id)
    {
        $customer = $this->find($id);

        return $customer->delete();
    }
}

