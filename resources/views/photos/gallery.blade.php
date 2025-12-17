@extends('layouts.app')

@section('content')
<h4>My Photos</h4>

@if($photos->count() == 0)
  <p>You don't have any photos yet.</p>
@endif

<div class="row">
  @foreach($photos as $photo)
    <div class="col-md-3 mb-3">
      <div class="card">
        <img src="{{ asset('storage/' . $photo->thumb_path) }}" class="card-img-top" style="height:160px; object-fit:cover;">
        <div class="card-body">
          <p class="card-text small text-muted">Frame: {{ $photo->frame ? $photo->frame->name : 'None' }}</p>
          <a href="{{ url('/photos/'.$photo->id) }}" class="btn btn-sm btn-primary">View</a>
          <!-- Edit & Delete features will be added later -->
        </div>
      </div>
    </div>
  @endforeach
</div>

<div class="mt-3">
  {{ $photos->links() }}
</div>
@endsection
