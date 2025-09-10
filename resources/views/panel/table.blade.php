<div class="toolbar">
<input type="text" placeholder="Caută IP/UA/vector" wire:model.live.debounce.400ms="search"/>
<select wire:model.live="type"><option value="">Toate tipurile</option><option value="form">form</option><option value="route">route</option><option value="canary">canary</option></select>
<select wire:model.live="perPage"><option>25</option><option>50</option><option>100</option></select>
<button wire:click="exportJson">Export JSON</button>
<button wire:click="purgeOld">Purge</button>
</div>
<table>
<thead><tr><th>#</th><th>type</th><th>vector</th><th>ip</th><th>ua</th><th>created_at</th><th></th></tr></thead>
<tbody>
@foreach($events as $e)
<tr>
<td>{{ $e->id }}</td>
<td><span class="pill">{{ $e->type }}</span></td>
<td>{{ $e->vector }}</td>
<td>{{ $e->ip }}</td>
<td title="{{ $e->ua }}">{{ \Illuminate\Support\Str::limit($e->ua, 50) }}</td>
<td>{{ $e->created_at }}</td>
<td><button wire:click="blockIp('{{ $e->ip }}')">Block IP</button></td>
</tr>
@endforeach
</tbody>
</table>
<div style="margin-top:12px">{{ $events->links() }}</div>