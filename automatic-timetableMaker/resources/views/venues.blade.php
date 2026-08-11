@extends('layouts.app')
@section('title', 'Venues')

@section('content')
<div class="card border-0 shadow-sm p-3 mb-4">
    <h5 class="card-title mb-3"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Add Room / Venue</h5>
    <form class="row g-3" action="{{ route('venues.store') }}" method="POST">
        @csrf
        <div class="col-md-4">
            <input type="text" class="form-control" name="room_name" placeholder="Room Name (e.g. Lab 3B)">
        </div>
        <div class="col-md-4">
            <input type="number" class="form-control" name="capacity" placeholder="Seating Capacity">
        </div>
        <div class="col-md-4">
            <select class="form-select" name="room_type">
                <option>Lecture Hall</option>
                <option>Computer Lab</option>
                <option>Auditorium</option>
            </select>
        </div>
        <div class="col-12">
            <button class="btn btn-primary"><i class="bi bi-building-add me-1"></i>Save Venue</button>
        </div>
    </form>
</div>
@endsection