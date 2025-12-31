<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateHasCategorys;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        if (auth()->user()->hasPermissionTo('certificates.index.full')) {
            $pendingCertificates = Certificate::where('status', 'pending')->with('user')->latest()->paginate(8);

            $certificateHistory = Certificate::when(request()->q, function ($query) {
                $query->where(function ($query) {
                    $query->whereHas('user', function ($query) {
                        $query->where('first_name', 'like', '%' . request()->q . '%')
                            ->orWhere('surname', 'like', '%' . request()->q . '%');
                    })->orWhere('status', 'like', '%' . request()->q . '%');
                });
            })->where(function ($query) {
                $query->where('status', 'approved')->orWhere('status', 'rejected');
            })->with('user')->latest()->paginate(15);

            $certificateHistory->appends(['q' => request()->q]);
        } else {
            $pendingCertificates = Certificate::where('user_id', auth()->user()->id)
                ->with('user')
                ->latest()
                ->paginate(10);

            $certificateHistory = Certificate::where('user_id', auth()->user()->id)
                ->with('user')
                ->latest()
                ->paginate(15);
        }

        return inertia('Account/Certificates', [
            'pendingCertificates' => $pendingCertificates,
            'categories' => $categories,
            'certificateHistory' => $certificateHistory
        ]);
    }


    public function store(Request $request, CertificateHasCategorys $certificateHasCategorys, Certificate $certificate)
    {
        $this->validate($request, [
            'certificateId' => 'required',
            'status' => 'required',
        ]);

        if ($request->status == 'approved') {
            $this->validate($request, [
                'certificate_categories' => 'required',
            ], [
                'certificate_categories.required' => 'Please select at least one category'
            ]);

            foreach ($request->certificate_categories as $certCategory) {
                $certificateHasCategorys->create([
                    'certificate_id' => $request->certificateId,
                    'category_id' => $certCategory
                ]);
            };

            $certificate->where('id', $request->certificateId)->update([
                'status' => 'approved',
                'description' => $request->massage ?? '',
            ]);
        } else if ($request->status == 'rejected') {
            $this->validate($request, [
                'massage' => 'required',
            ]);

            $path = parse_url($request->selectedCertificate, PHP_URL_PATH);
            $storagePath = str_replace('/storage', 'public', $path);

            $certificate->where('id', $request->certificateId)->update([
                'status' => 'rejected',
                'description' => $request->massage
            ]);

            Storage::disk('local')->delete($storagePath);
        }

        return redirect()->route('account.certificates.index');
    }
}
