@extends('layouts.master')

@section('content')
    <div class="container py-2">
        <div class="card py-2">
            <h2>مشاهدة زائري المدرسة خلال الشهر الحالي</h2>
            <div class="row">
                <div class="col-md-6">
                    <canvas id="myChart" width="400" height="400"></canvas>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Extract the data from PHP and convert it to JavaScript format
        var schoolVisits = @json($schoolvisits);

        // Prepare the chart data
        var labels = schoolVisits.map(function (visit) {
            return visit.start; // Assuming you want to use the 'start' date as the label
        });

        var counts = schoolVisits.map(function (visit) {
            return 1; // Assuming you want to count the number of visits per day (each object represents a visit)
        });

        // Create the chart
        var ctx = document.getElementById('myChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Number of Visitors',
                    data: counts,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });


    </script>
@endsection
