<div>
    <div>
        <p class="text-xl pb-3 flex items-center">
        <div class="text-center text-2xl">
            Measurements for {{$start}} - {{$end}}
            <br/>
        </div>
        <br/>
    </div>
    <div class=" ">
        <div class="col-span-6 sm:col-span-6 md:col-span-4 lg:col-span-3">
            <label for="rangeSelected" value="Date Range"/>
            <select
                wire:model="rangeSelected"
                class=" max-w-md  mt-1 block  border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                @foreach ($dateRange as $key => $value)
                    <option
                        value="{{$value}}">{{$key}}</option>
                @endforeach
            </select>
        </div>
        <br/>
        @include('partials.measurement-table',  ['editingEnabled' => $editingEnabled, 'editEntry' => $editEntry])
    </div>
</div>
