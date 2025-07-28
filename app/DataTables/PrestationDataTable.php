<?php

namespace App\DataTables;

use App\CustomField;
use App\Prestation;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class PrestationDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        $customFields = CustomField::where('model_type', Prestation::class)->get();

        $dataTable->addColumn('action', 'prestations.datatables_actions')
            ->editColumn('created_by', function (Prestation $prestation) {
                return $prestation->createdBy->name ?? '';
            })
            ->filterColumn('created_by', function ($query, $keyword) {
                $query->whereHas('createdBy', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            });

        foreach ($customFields as $customField) {
            $dataTable->addColumn($customField->name, function (Prestation $prestation) use ($customField) {
                return $prestation->custom_fields[$customField->name] ?? '';
            });
        }

        // Ensure action column is rendered as HTML
        $rawColumns = ['action'];
        foreach ($customFields as $customField) {
            // You might need to add custom fields to rawColumns if they contain HTML
            // $rawColumns[] = $customField->name;
        }
        return $dataTable->rawColumns($rawColumns);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Prestation $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Prestation $model)
    {
        return $model->newQuery()->with('createdBy');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '120px', 'printable' => false, 'title' => 'Action'])
            ->parameters([
                'dom'       => 'Bfrtip',
                'stateSave' => true,
                'order'     => [[0, 'desc']],
                'buttons'   => [
                    ['extend' => 'export', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'print', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'reset', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'reload', 'className' => 'btn btn-default btn-sm no-corner',],
                ],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            'id' => ['title' => 'ID'],
            'code' => ['title' => 'Code'],
            'label' => ['title' => 'Libellé'],
            'created_by' => ['title' => 'Créé par', 'name' => 'createdBy.name', 'orderable' => false],
            'created_at' => ['title' => 'Créé le'],
        ];

        $customFields = CustomField::where('model_type', Prestation::class)->get();

        foreach ($customFields as $customField) {
            $columns[$customField->name] = [
                'data' => $customField->name,
                'name' => $customField->name,
                'title' => $customField->name,
                'orderable' => false, // Custom fields are often not easily orderable at DB level
                'searchable' => false, // Or implement custom filtering
            ];
        }

        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'prestationsdatatable_' . time();
    }
}
