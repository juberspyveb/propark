<?php
namespace App\Exports;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class UsersExport implements FromCollection,WithHeadings
{

public function headings(): array {
    return [
       "Id","Unique ID","First Name","Last Name","Email","Address","City","Country ID","Phone Number" ,"Created AT"
    ];
  }
  public function collection()
  {
  	$product = DB::table('users')->select('id','user_unique_id','first_name','last_name','email','address','city','Country_id','phone_number' ,'created_at')
  		->where('user_role_id' ,'=','2')
  		->get();

    return $product;
  }
}