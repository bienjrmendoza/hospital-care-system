@extends('layouts.app')

@section('content')
<div class="back-btn admin-btn mb-3">
    <button class="back-btn" id="backBtn">
        <i class="fa-solid fa-arrow-left"></i> Back
    </button>
</div>

<h3 class="text-secondary mb-3">Generate Vital Signs Report</h3>

<div class="card mb-4">
    <div class="card-body admin-btn">
        <form id="pdfForm" method="POST" action="{{ route('admin.vitals.export') }}" target="_blank">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Select Patient</label>
                    <select class="form-select shadow-none" name="user_id" required>
                        <option value="">Choose patient</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control shadow-none" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Temperature (°C)</label>
                    <input type="text" name="temperature" class="form-control shadow-none">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Heart Rate (bpm)</label>
                    <input type="number" name="heart_rate" class="form-control shadow-none">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Respiratory Rate</label>
                    <input type="number" name="respiratory_rate" class="form-control shadow-none">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Blood Pressure</label>
                    <input type="text" name="blood_pressure" class="form-control shadow-none" placeholder="120/80">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Oxygen Saturation (%)</label>
                    <input type="number" name="oxygen_saturation" class="form-control shadow-none">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Weight (kg)</label>
                    <input type="text" id="weight" name="weight" class="form-control shadow-none">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Height (cm)</label>
                    <input type="text" id="height" name="height" class="form-control shadow-none">
                </div>
                <div class="col-md-4">
                    <label class="form-label">BMI</label>
                    <input type="text" id="bmi" name="bmi" class="form-control shadow-none" readonly placeholder="Calculated automatically">
                </div>
                <div class="col-md-4">
                    <label class="form-label">History of Patient</label>
                    <textarea name="notes" rows="3" class="form-control shadow-none"></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Initial Assessment</label>
                    <textarea name="initial_assessment" rows="3" class="form-control shadow-none"></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Laboratory Examination / Diagnostic</label>
                    <textarea name="diagnostic" rows="3" class="form-control shadow-none"></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Medication</label>
                    <textarea name="medication" rows="3" class="form-control shadow-none"></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Treatment</label>
                    <textarea name="treatment" rows="3" class="form-control shadow-none"></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Diet</label>
                    <textarea name="diet" rows="3" class="form-control shadow-none"></textarea>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Recommendation / Remarks</label>
                    <textarea name="remarks" rows="3" class="form-control shadow-none"></textarea>
                </div>
                <div class="col-md-12 admin-btn">
                    <button class="bg-primary text-white secondary-hover">
                        <i class="fa-solid fa-file-pdf"></i> Generate PDF
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card" id="history">
    <div class="card-body">
        <h5 class="text-secondary mb-3">History of Generated PDFs</h5>
        @if($vitals->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Email</th>
                        <th>Generated At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vitals as $vital)
                    <tr>
                        <td>{{ $vital->date }}</td>
                        <td>{{ $vital->user->name }}</td>
                        <td>{{ $vital->user->email }}</td>
                        <td>{{ $vital->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.vitals.view', ['vital_id'=>$vital->id]) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                View PDF
                            </a>
                            <form action="{{ route('admin.vitals.delete', $vital->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button id="confirmDeleteSpecializationBtn" class="btn btn-sm btn-outline-danger mb-0" data-id="{{ $vital->id }}">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p class="text-muted">No vitals reports generated yet.</p>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function calculateBMI(){
        let weight = parseFloat(document.getElementById('weight').value);
        let height = parseFloat(document.getElementById('height').value);
        if(weight && height){
            height = height / 100;
            let bmi = weight / (height * height);
            document.getElementById('bmi').value = bmi.toFixed(2);
        } else {
            document.getElementById('bmi').value = '';
        }
    }

    document.getElementById('weight').addEventListener('input', calculateBMI);
    document.getElementById('height').addEventListener('input', calculateBMI);
    
    const pdfForm = document.getElementById('pdfForm');
    pdfForm.addEventListener('submit', function(e) {
        const toast = document.createElement('div');
        toast.innerText = 'PDF is being generated...';
        toast.className = 'toast-notification';
        document.body.appendChild(toast);

        setTimeout(() => toast.remove(), 3000);

        setTimeout(() => {
            pdfForm.reset();
            document.getElementById('bmi').value = '';
        }, 500);
    });
</script>
@endpush