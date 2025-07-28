<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\PrestationDataTable;
use App\Repositories\PermissionRepository;
use App\Repositories\PrestationRepository;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Carbon\CarbonPeriod;    
use Illuminate\Support\Collection;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use App\Prestation;
use App\PrestationLog;
use App\User;
use Flash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Response;
use Spatie\Permission\Models\Permission;
class PrestationController extends Controller
{

    /** @var PrestationRepository */
    private $prestationRepo;
    /** @var PermissionRepository */
    private $permissionRepository;
    public function __construct(PrestationRepository $prestationRepo,
                                PermissionRepository $permissionRepository)
    {
        $this->prestationRepo = $prestationRepo;
        $this->permissionRepository = $permissionRepository;
    }

    public function dailyForm()
    {
        $prestations = Prestation::all();
        return view('prestations.daily', compact('prestations'));
    }
    
    public function dailyStore(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'quantite' => 'array',
            'quantite.*' => 'nullable|numeric|min:0',
        ]);

        $quantites = $request->input('quantite', []);
        if (is_array($request->quantite)) {
            foreach ($request->quantite as $prestation_id => $quantite) {
                if ($quantite && $quantite > 0) {
                    PrestationLog::create([
                        'prestation_id' => $prestation_id,
                        'date' => $request->date,
                        'quantite' => $quantite,
                        'user_id' => auth()->id(), // ici on ajoute l'utilisateur connecté
                    ]);
                }
            }
        }
        return redirect()->back()->with('success', 'Prestations enregistrées.');
    }
    public function journal()
    {
        // On récupère les prestations avec leurs noms et dates groupées
        $logs = PrestationLog::with('prestation')
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy('date'); // Regroupe par jour

        return view('prestations.pivot', compact('logs'));
    }

    
    public function pivotJournal(Request $request)
    {
        // 1. Get date range from request or set defaults
        try {
            $startDate = Carbon::parse($request->input('date_from', Carbon::now()->subDays(6)));
            $endDate = Carbon::parse($request->input('date_to', Carbon::now()));
        } catch (\Exception $e) {
            // Fallback in case of invalid date format
            $startDate = Carbon::now()->subDays(6);
            $endDate = Carbon::now();
        }

        // 2. Get all relevant prestations
        $prestations = Prestation::all();
        $codes = $prestations->pluck('code')->unique()->values();

        //dd($prestations);
        // 3. Build a performant pivot query
        $pivotQuery = PrestationLog::query()
            ->select('date')
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc');

        foreach ($prestations as $prestation) {
            // Ensure the alias is safe to use.
            $alias = preg_replace('/[^a-zA-Z0-9_]/', '', $prestation->code);
            if (!empty($alias)) {
                $pivotQuery->selectRaw(
                    "SUM(CASE WHEN prestation_id = ? THEN quantite ELSE 0 END) as `{$alias}`",
                    [$prestation->id]
                );
            }
        }
          
        $logs = $pivotQuery->get()->keyBy(function($item){
            return Carbon::parse($item->date)->format('Y-m-d');
        })->toArray();

        // 4. Fill missing dates to have a continuous timeline
        $period = CarbonPeriod::create($startDate, $endDate);
        $pivot = [];
        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            if (isset($logs[$formattedDate])) {
                // Clean up the row, remove the original date field
                unset($logs[$formattedDate]['date']);
                $pivot[$formattedDate] = $logs[$formattedDate];
            } else {
                // Create a row with 0 values for missing dates
                $emptyRow = [];
                foreach ($codes as $code) {
                    $emptyRow[$code] = 0;
                }
                $pivot[$formattedDate] = $emptyRow;
            }
        }

        return view('prestations.pivot', [
            'pivot' => $pivot,
            'codes' => $codes->toArray(),
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
/*
    public function index()
    {
        $prestations = Prestation::all();
        return view('prestations.index', compact('prestations'));
    }
*/
    public function types()
    {
        $prestations = Prestation::all();
        return view('prestations.types', compact('prestations'));
    }

    public function index(PrestationDataTable $prestationDataTable)
    {
        
        if ($prestationDataTable->ajax()) {
            $query = PrestationLog::getDynamicPivotData();
            return DataTables::of($query)->make(true);
        }

        $codes = PrestationLog::prestationCodes();
        //return $prestationDataTable->render('prestations.pivote');
        return view('prestations.pivot', compact('codes', 'prestations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:prestations',
            'label' => 'required',
        ]);

        Prestation::create($request->only('code', 'label'));

        return redirect()->back()->with('success', 'Prestation ajoutée.');
    }

        /**
     * Display the specified Tag.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $prestation = $this->prestationRepo->find($id);
        $this->authorize('view', $prestation);

        if (empty($tag)) {
            Flash::error(ucfirst(config('settings.prestations_label_singular')) . ' not found');

            return redirect(route('prestations.index'));
        }
        return view('prestations.show', compact('prestation'));
    }   


    public function logForm()
    {
        $prestations = Prestation::all();
        return view('prestations.log', compact('prestations'));
    }

    public function saveLog(Request $request)
    {
        $request->validate([
            'prestation_id' => 'required|exists:prestations,id',
            'date' => 'required|date',
            'quantite' => 'required|numeric|min:1',
        ]);

        PrestationLog::create($request->only('prestation_id', 'date', 'quantite'));

        return redirect()->back()->with('success', 'Prestation enregistrée.');
    }
}
