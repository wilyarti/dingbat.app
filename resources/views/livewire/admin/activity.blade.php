@push('head')
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
@endpush
<div>
    <h2>Active Plans</h2>
    <div>
        <livewire:datatable
            model="App\Models\active_plan"
            include="active_plan_id, plan, user, start_date, created_at"
            dates="start_date,created_at"
            searchable="user, plan"
            exportable
        />
    </div>
    <br/>
    <h2>Users</h2>
    <div>
        <livewire:datatable
            model="App\Models\User"
            include="id, name, email, timezone, created_at"
            dates="created_at"
            searchable="id, name, email"
            exportable
        />
    </div>
    <br/>
    <h2>Logging</h2>
    <div>
        <livewire:datatable
            model="Spatie\Activitylog\Models\Activity"
            include="id, log_name, description, created_at"
            dates="created_at"
            hideable="description"
            searchable="log_name, description"
            exportable
        />
    </div>
    <br/>
    <h2>Plans</h2>
    <div>
        <livewire:datatable
            model="App\Models\plan"
            include="plan_id, plan_name, user_id, owner"
            dates="created_at"
            searchable="description, user_id"
            exportable
        />
    </div>
    <br/>
    <h2>Sets</h2>
    <div>
        <livewire:datatable
            model="App\Models\set"
            include="set_id, user_id, plan_id, one_rep_max, weight,date"
            dates="date"
            searchable="set_id, user_id, plan_id"
            exportable
        />
    </div>
    <br/>

</div>
