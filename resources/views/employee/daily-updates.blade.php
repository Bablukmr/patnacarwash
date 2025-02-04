<table id="dailyUpdatesTable" class="table table-bordered">
    <thead>
        <tr>
            <th>Date</th>
            <th>Status</th>
            <th>Location</th>
            <th>Notes</th>
            <th>Images</th>
        </tr>
    </thead>
    <tbody>
        @foreach($updates as $update)
        <tr>
            <td>{{ $update->date }}</td>
            <td>
                <span class="badge badge-{{ 
                    $update->status == 'completed' ? 'success' : 
                    ($update->status == 'in_progress' ? 'warning' : 'secondary') 
                }}">
                    {{ ucfirst(str_replace('_', ' ', $update->status)) }}
                </span>
            </td>
            <td>
                {{ $update->location_address }}
                <small class="text-muted d-block">
                    {{ $update->latitude }}, {{ $update->longitude }}
                </small>
            </td>
            <td>{{ $update->notes }}</td>
            <td>
                @foreach(json_decode($update->images) as $image)
                <a href="{{ asset('storage/'.$image) }}" target="_blank">
                    <img src="{{ asset('storage/'.$image) }}" 
                         class="img-thumbnail" 
                         style="max-width: 100px;">
                </a>
                @endforeach
            </td>
        </tr>
        @endforeach
    </tbody>
</table>