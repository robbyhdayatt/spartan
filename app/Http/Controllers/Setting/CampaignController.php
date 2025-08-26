<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\Campaign;
use App\Http\Requests\StoreCampaignRequest;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public function index()
    {
        $this->authorize('access', ['settings.campaign', 'read']);
        $campaigns = Campaign::latest()->paginate(15);
        return view('setting.campaign.index', compact('campaigns'));
    }

    public function store(StoreCampaignRequest $request)
    {
        $this->authorize('access', ['settings.campaign', 'create']);
        $validated = $request->validated();
        $validated['kode_campaign'] = 'CAMP-' . strtoupper(Str::random(8));
        $validated['created_by'] = auth()->id();

        Campaign::create($validated);
        return redirect()->route('campaign.index')->with('success', 'Program kampanye berhasil ditambahkan.');
    }

    public function update(StoreCampaignRequest $request, Campaign $campaign)
    {
        $this->authorize('access', ['settings.campaign', 'update']);
        $campaign->update($request->validated());
        return redirect()->route('campaign.index')->with('success', 'Program kampanye berhasil diperbarui.');
    }

    public function destroy(Campaign $campaign)
    {
        $this->authorize('access', ['settings.campaign', 'delete']);
        $campaign->delete();
        return redirect()->route('campaign.index')->with('success', 'Program kampanye berhasil dihapus.');
    }
}