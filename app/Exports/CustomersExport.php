<?php
namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use App\Customer;
class CustomersExport implements FromQuery
{
    public function __construct(int $restaurant_id)
    {
        $this->restaurant_id = $restaurant_id;
    }
    public function query()
    {
        return Customer::query()->select('name','phone', 'email')->where('restaurant_id', $this->restaurant_id);
    }
}