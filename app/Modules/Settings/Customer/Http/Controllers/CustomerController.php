<?php

namespace App\Modules\Settings\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Settings\Customer\Services\CustomerService;

class CustomerController extends Controller
{
    public function __construct(
        protected CustomerService $customerService
    ) {
    }

    public function index()
    {
        $customers = $this->customerService->getAll();

        return view('customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:150',
        ]);

        $this->customerService->create($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'مشتری با موفقیت ثبت شد.');
    }

    public function show(int $id)
    {
        $customer = $this->customerService->find($id);

        return view('customers.show', compact('customer'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:150',
        ]);

        $this->customerService->update($id, $validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'اطلاعات مشتری با موفقیت بروزرسانی شد.');
    }

    public function destroy(int $id)
    {
        $this->customerService->delete($id);

        return redirect()
            ->route('customers.index')
            ->with('success', 'مشتری با موفقیت حذف شد.');
    }
}