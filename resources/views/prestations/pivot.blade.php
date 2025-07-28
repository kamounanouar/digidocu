@extends('layouts.app')


@section('title','Pivot Show'.ucfirst(config('settings.tags_label_singular')))

@section('content_header')
    <h1 class="pull-left">
        {{ucfirst(config('settings.Journal_prestations'))}}
        </h1>
    <h1 class="pull-right">
            @can('create prestations')
                <a class="btn btn-flat"
                   href="{!! route('prestations.daily.form') !!}">
                    <i class="fas fa-lg fa-plus"></i>
                    Nouveau
            </a>
        @endcan
    </h1>
    <div class="clearfix"></div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Graphique des prestations</h2>
                    <div class="form-group">
                        <label for="chartTypeSelector">Type de graphique :</label>
                        <select id="chartTypeSelector" class="form-control" style="width: auto;">
                            <option value="bar" selected>Barres</option>
                            <option value="line">Lignes</option>
                        </select>
                    </div>
                </div>
                    <div class="card-body">
                        <canvas id="pivotChart"  class="my-4 w-100 chartjs-render-monitor"></canvas>
                
            <button onclick="downloadChart()">📥 Exporter en image</button>

            

        <div class="card card-primary">
            <div class="card-body">
                <form method="GET" action="{{ route('prestations.pivot') }}" class="form-inline">
                    <div class="form-group mb-2">
                        <label for="date_from" class="mr-2">Du :</label>
                        <input type="date" id="date_from" name="date_from" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="form-group mx-sm-3 mb-2">
                        <label for="date_to" class="mr-2">Au :</label>
                        <input type="date" id="date_to" name="date_to" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Filtrer</button>
                </form>
            <br>
            <div class="tab-pane" id="tab_permissions">
                <div>
                    <table border="1" cellpadding="5" cellspacing="0" id="datatable" class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                @foreach ($codes as $code)
                                    <th>{{ $code }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pivot as $date => $row)
                                <tr>
                                    <td>{{ $date }}</td>
                                    @foreach ($codes as $code)
                                        <td>{{ $row[$code] ?? 0 }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @section('plugins.Datatables', true)
        <div class="text-center"></div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />
  
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>

<script>
    const chartLabels = {!! json_encode(array_keys($pivot)) !!};
    const rawData = {!! json_encode($pivot) !!};
    const codes = {!! json_encode($codes) !!};

    // Helper to convert hex to rgba
    const hexToRgba = (hex, alpha) => {
        const r = parseInt(hex.slice(1, 3), 16);
        const g = parseInt(hex.slice(3, 5), 16);
        const b = parseInt(hex.slice(5, 7), 16);
        return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    };

    let allDatasets = codes.map(code => {
        const color = getRandomColor();
        return {
            label: code,
            data: chartLabels.map(date => rawData[date]?.[code] ?? 0),
            borderColor: color,
            backgroundColor: hexToRgba(color, 0.5), // Use semi-transparent background
            fill: false,
            tension: 0.3,
            hidden: false // on affiche tout par défaut
        }
    });

    const ctx = document.getElementById('pivotChart');
    const pivotChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: allDatasets
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Évolution des prestations (par code)'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // ✅ Gestion du sélecteur de type de graphique
    document.getElementById('chartTypeSelector').addEventListener('change', function() {
        pivotChart.config.type = this.value;
        pivotChart.update();
    });

    // ✅ Gestion des cases à cocher
    document.querySelectorAll('.code-toggle').forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const code = checkbox.value;
            const ds = pivotChart.data.datasets.find(d => d.label === code);
            if (ds) {
                ds.hidden = !checkbox.checked;
                pivotChart.update();
            }
        });
    });

    // ✅ Export PNG
    function downloadChart() {
        const link = document.createElement('a');
        link.href = pivotChart.toBase64Image();
        link.download = 'graphique_prestations.png';
        link.click();
    }

    // 🎨 Couleurs aléatoires
    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) color += letters[Math.floor(Math.random() * 16)];
        return color;
    }
    let table = new DataTable('#datatable');
</script>

@endsection
