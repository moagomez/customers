<?php

namespace App\Http\Controllers;

use App\Company;
use App\Customer;
use App\Events\CustomerHasRegisteredEvent;
use App\Mail\WelcomeUserMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class customerController extends Controller
{
    public function index () {
        $customers=Customer::all();
        return view('customers.index',compact('customers'));
    }
    public function create(){
        $companies = Company::all();
        $customer= new Customer();
       return view('customers.create',compact('companies','customer'));

    }
    public function store() {
        $customer=Customer::create($this->vaildaterequest());
        event(new CustomerHasRegisteredEvent($customer));



        return redirect('customers');
    }
    public function show(Customer $customer){
        // we don't need this cuz we used route model biding who will make fetch exteremly simple in the function "show(Customer $customer)" so we dont't use that $customer=Customer::where('id',$customer)->firstorfail();
        return view('customers.show', compact('customer'));
    }
    public function edit (Customer $customer){
        $companies = Company::all();
        return view('customers.edit', compact('customer', 'companies'));
    }
    public function update (Customer $customer)
    {
    $customer->update($this->vaildaterequest());
        return redirect('customers/'.$customer->id);
    }
    public function destroy (Customer $customer){
        $customer->delete();
        return redirect('customers');
    }
    private function vaildaterequest(){
        return request()->validate([
            'name'=>'required|min:3',
            'email' => 'required|email',
            'active'=>'required',
            'company_id' =>'required',
        ]);
    }

}
